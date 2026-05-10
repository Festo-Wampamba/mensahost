<?php
session_start();
require_once 'includes/contact_db.php';
require_once 'includes/smtp_mailer.php';

/* ── CSRF token ── */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors  = [];
$success = false;
$old     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CSRF check */
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {

        $old['name']    = trim($_POST['name']    ?? '');
        $old['email']   = trim($_POST['email']   ?? '');
        $old['subject'] = trim($_POST['subject'] ?? '');
        $old['message'] = trim($_POST['message'] ?? '');

        /* Validate */
        if ($old['name'] === '')                                       $errors[] = 'Name is required.';
        if ($old['email'] === '')                                      $errors[] = 'Email is required.';
        elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL))     $errors[] = 'Email address is not valid.';
        if ($old['subject'] === '')                                    $errors[] = 'Subject is required.';
        if ($old['message'] === '')                                    $errors[] = 'Message is required.';

        if (empty($errors)) {
            /* Save to DB */
            $stmt = $pdo->prepare(
                "INSERT INTO contact_messages (name, email, subject, message)
                 VALUES (:name, :email, :subject, :message)"
            );
            $stmt->execute([
                ':name'    => $old['name'],
                ':email'   => $old['email'],
                ':subject' => $old['subject'],
                ':message' => $old['message'],
            ]);
            $newId = $pdo->lastInsertId();

            $mailer    = new SmtpMailer('festo@mensahost.tech', 'MensaHost');
            $adminLink = "https://mensahost.tech/admin/messages.php?id={$newId}";

            /* Admin notification */
            $adminBody = "New contact form submission — MensaHost\n"
                       . "=========================================\n"
                       . "Name:    {$old['name']}\n"
                       . "Email:   {$old['email']}\n"
                       . "Subject: {$old['subject']}\n\n"
                       . "Message:\n{$old['message']}\n\n"
                       . "-----------------------------------------\n"
                       . "View & reply: {$adminLink}\n"
                       . "--\nMensaHost Contact System";

            $mailer->send(
                'festo@mensahost.tech',
                'Festo',
                "[MensaHost Contact] {$old['subject']}",
                $adminBody
            );

            /* Auto-reply to submitter */
            $autoReply = "Hi {$old['name']},\n\n"
                       . "Thank you for reaching out to MensaHost. We have received your message\n"
                       . "and will get back to you within 24 hours.\n\n"
                       . "Here is a copy of what you sent:\n"
                       . "-----------------------------------------\n"
                       . "Subject: {$old['subject']}\n\n"
                       . "{$old['message']}\n"
                       . "-----------------------------------------\n\n"
                       . "If you have anything to add, simply reply to this email.\n\n"
                       . "Best regards,\n"
                       . "MensaHost Team\n"
                       . "https://mensahost.tech";

            $mailer->send(
                $old['email'],
                $old['name'],
                "We received your message — MensaHost",
                $autoReply
            );

            /* Rotate CSRF token */
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $success = true;
            $old     = [];
        }
    }
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — MensaHost</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:       #4F46E5;
            --primary-dark:  #3730a3;
            --secondary:     #10B981;
            --secondary-dark:#059669;
            --dark:          #0f172a;
            --text:          #1e293b;
            --muted:         #64748b;
            --light-bg:      #f8fafc;
            --card-bg:       #ffffff;
            --border:        #e2e8f0;
            --radius:        12px;
            --shadow:        0 4px 20px rgba(0,0,0,0.08);
            --shadow-hover:  0 10px 36px rgba(79,70,229,0.18);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text);
            background: #fff;
            scroll-behavior: smooth;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand { font-size: 1.4rem; font-weight: 800; color: var(--primary) !important; letter-spacing: -0.5px; }
        .navbar-brand span { color: var(--secondary); }
        .nav-link { font-size: 0.9rem; font-weight: 500; color: var(--text) !important; padding: 6px 16px !important; transition: color 0.2s; }
        .nav-link:hover { color: var(--primary) !important; }
        .nav-link.active { color: var(--primary) !important; }
        .nav-cta { background: var(--primary); color: #fff !important; border-radius: 8px; padding: 8px 20px !important; }
        .nav-cta:hover { background: var(--primary-dark); color: #fff !important; }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 45%, #312e81 70%, #4F46E5 100%);
            padding: 64px 0 56px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
            top: -120px; right: -80px;
            border-radius: 50%;
            pointer-events: none;
        }
        .page-badge {
            display: inline-block;
            background: rgba(16,185,129,0.15);
            color: #10B981;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 16px;
        }
        .page-title {
            font-size: clamp(1.9rem, 4vw, 3rem);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }
        .page-sub {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            max-width: 480px;
            line-height: 1.75;
        }

        /* ===== CONTACT SECTION ===== */
        .contact-section { padding: 72px 0; background: var(--light-bg); }

        .contact-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px 36px;
            box-shadow: var(--shadow);
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 18px 0;
            border-bottom: 1px solid var(--border);
        }
        .contact-info-item:last-child { border-bottom: none; }
        .info-icon {
            width: 44px; height: 44px;
            background: rgba(79,70,229,0.1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .info-label { font-size: 0.75rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .info-value { font-size: 0.92rem; font-weight: 500; color: var(--text); }

        /* ===== FORM ===== */
        .form-label { font-size: 0.87rem; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-control, .form-select {
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 11px 14px;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
            color: var(--text);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
            outline: none;
        }
        textarea.form-control { resize: vertical; min-height: 140px; }

        .btn-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 13px 32px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            width: 100%;
        }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }

        .alert-success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.3);
            color: #065f46;
            border-radius: var(--radius);
            padding: 18px 20px;
            font-size: 0.92rem;
            font-weight: 500;
        }
        .alert-error {
            background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.25);
            color: #991b1b;
            border-radius: var(--radius);
            padding: 18px 20px;
            font-size: 0.88rem;
        }
        .alert-error ul { margin: 6px 0 0 18px; padding: 0; }

        /* ===== FOOTER ===== */
        footer { background: var(--dark); padding: 52px 0 28px; }
        .footer-brand { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 10px; }
        .footer-brand span { color: var(--secondary); }
        .footer-tagline { font-size: 0.85rem; color: rgba(255,255,255,0.4); line-height: 1.65; }
        .footer-links { display: flex; gap: 24px; flex-wrap: wrap; }
        .footer-link { color: rgba(255,255,255,0.45); text-decoration: none; font-size: 0.85rem; transition: color 0.2s; }
        .footer-link:hover { color: #fff; }
        hr.footer-divider { border-color: rgba(255,255,255,0.08); margin: 36px 0 20px; }
        .footer-copy { font-size: 0.82rem; color: rgba(255,255,255,0.3); }

        @media (max-width: 767px) {
            .contact-card { padding: 28px 20px; }
            .footer-links { justify-content: center; }
            .footer-brand, .footer-tagline { text-align: center; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">Mensa<span>Host</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item"><a class="nav-link" href="index.php#plans">Plans</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#architecture">Stack</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#team">Team</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link nav-cta" href="index.php#team">Get Started</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== PAGE HEADER ===== -->
<div class="page-header">
    <div class="container">
        <div class="page-badge">Contact Us</div>
        <h1 class="page-title">Get in Touch</h1>
        <p class="page-sub">Have a question about hosting, need support, or want to learn more? Drop us a message — we'll get back to you quickly.</p>
    </div>
</div>

<!-- ===== CONTACT SECTION ===== -->
<section class="contact-section">
    <div class="container">
        <div class="row g-5 align-items-start">

            <!-- Left: contact info -->
            <div class="col-lg-4">
                <div class="contact-card">
                    <h2 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:4px;">Contact Information</h2>
                    <p style="font-size:0.85rem;color:var(--muted);margin-bottom:8px;">Reach out through any of these channels.</p>

                    <div class="contact-info-item">
                        <div class="info-icon">📧</div>
                        <div>
                            <div class="info-label">Email</div>
                            <div class="info-value">festo@mensahost.tech</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon">🌐</div>
                        <div>
                            <div class="info-label">Website</div>
                            <div class="info-value">mensahost.tech</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon">🏫</div>
                        <div>
                            <div class="info-label">Institution</div>
                            <div class="info-value">Makerere University Business School</div>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="info-icon">⏱️</div>
                        <div>
                            <div class="info-label">Response Time</div>
                            <div class="info-value">Usually within 24 hours</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: form -->
            <div class="col-lg-8">
                <div class="contact-card">
                    <h2 style="font-size:1.15rem;font-weight:700;color:var(--dark);margin-bottom:4px;">Send a Message</h2>
                    <p style="font-size:0.85rem;color:var(--muted);margin-bottom:28px;">All fields are required. We will never share your information.</p>

                    <?php if ($success): ?>
                        <div class="alert-success">
                            ✅ <strong>Message sent!</strong> Thank you — we've received your message and sent a confirmation to your email. We'll be in touch soon.
                        </div>
                    <?php else: ?>

                        <?php if (!empty($errors)): ?>
                            <div class="alert-error">
                                <strong>Please fix the following:</strong>
                                <ul>
                                    <?php foreach ($errors as $e): ?>
                                        <li><?= h($e) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div style="margin-top:20px;"></div>
                        <?php endif; ?>

                        <form method="POST" action="contact.php" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Your full name"
                                           value="<?= h($old['name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="you@example.com"
                                           value="<?= h($old['email'] ?? '') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="subject">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject"
                                           placeholder="What is this about?"
                                           value="<?= h($old['subject'] ?? '') ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message"
                                              placeholder="Write your message here…" required><?= h($old['message'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-submit">Send Message →</button>
                                </div>
                            </div>
                        </form>

                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
    <div class="container">
        <div class="row align-items-start g-4">
            <div class="col-md-6">
                <div class="footer-brand">Mensa<span>Host</span></div>
                <p class="footer-tagline">
                    Smart Hosting. Zero Limits.<br>
                    Built for BBC MENSA &mdash; Web Server Administration, Makerere University Business School.
                </p>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy</a>
                    <a href="#" class="footer-link">Terms</a>
                    <a href="contact.php" class="footer-link">Contact</a>
                    <a href="index.php#team" class="footer-link">Our Team</a>
                </div>
            </div>
        </div>
        <hr class="footer-divider">
        <p class="footer-copy">
            &copy; <?php echo date('Y'); ?> MensaHost. All rights reserved. &nbsp;|&nbsp;
            BBC MENSA &mdash; AY&nbsp;2025/2026 Semester II
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
