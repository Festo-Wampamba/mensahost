<?php
// 1. Include db.php — establishes $pdo (PDO instance) connected to mensa_db
require_once 'db.php';

// 2. Query all members from the database
$members   = [];
$db_error  = false;

try {
    $stmt    = $pdo->query("SELECT * FROM members ORDER BY id");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("MensaHost members query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MensaHost — Smart Hosting. Zero Limits.</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ===== CSS VARIABLES ===== */
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
        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary) !important;
            letter-spacing: -0.5px;
        }
        .navbar-brand span { color: var(--secondary); }
        .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text) !important;
            padding: 6px 16px !important;
            transition: color 0.2s;
        }
        .nav-link:hover { color: var(--primary) !important; }
        .nav-cta {
            background: var(--primary);
            color: #fff !important;
            border-radius: 8px;
            padding: 8px 20px !important;
        }
        .nav-cta:hover { background: var(--primary-dark); color: #fff !important; }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 45%, #312e81 70%, #4F46E5 100%);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        /* Decorative radial glow */
        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(16,185,129,0.15);
            color: #10B981;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        .hero-title span { color: var(--secondary); }
        .hero-sub {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.75);
            font-weight: 400;
            line-height: 1.75;
            margin-bottom: 36px;
            max-width: 480px;
        }
        .btn-hero-primary {
            background: var(--secondary);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px 32px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-hero-primary:hover {
            background: var(--secondary-dark);
            color: #fff;
            transform: translateY(-2px);
        }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: 10px;
            padding: 12px 30px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-hero-outline:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.08);
            color: #fff;
        }
        /* Right-side illustration placeholder */
        .hero-visual {
            background: linear-gradient(135deg, rgba(79,70,229,0.4), rgba(16,185,129,0.3));
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        /* Subtle grid pattern inside visual box */
        .hero-visual::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                45deg,
                rgba(255,255,255,0.02) 0px,
                rgba(255,255,255,0.02) 1px,
                transparent 1px,
                transparent 20px
            );
        }
        .hero-icon-wrap {
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .hero-server-icon { font-size: 5rem; display: block; margin-bottom: 12px; }
        .hero-icon-label {
            color: rgba(255,255,255,0.65);
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        /* Floating info badges on the illustration */
        .badge-float {
            position: absolute;
            background: rgba(16,185,129,0.88);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            z-index: 2;
        }
        .badge-float.top-left   { top: 18px; left: 18px; }
        .badge-float.bottom-right { bottom: 18px; right: 18px; background: rgba(79,70,229,0.9); }

        /* ===== STATS STRIP ===== */
        .stats-strip {
            background: var(--dark);
            padding: 28px 0;
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 20px;
            border-right: 1px solid rgba(255,255,255,0.08);
        }
        .stat-item:last-child { border-right: none; }
        .stat-icon {
            width: 44px;
            height: 44px;
            background: rgba(79,70,229,0.22);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .stat-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1.2;
        }
        .stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.3;
        }

        /* ===== SHARED SECTION STYLES ===== */
        section { padding: 80px 0; }
        .section-badge {
            display: inline-block;
            background: rgba(79,70,229,0.1);
            color: var(--primary);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 14px;
        }
        .section-title {
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.5px;
            margin-bottom: 14px;
        }
        .section-sub {
            font-size: 1rem;
            color: var(--muted);
            max-width: 540px;
            margin: 0 auto 48px;
            line-height: 1.7;
        }

        /* ===== PLANS ===== */
        #plans { background: var(--light-bg); }
        .plan-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 36px 28px;
            height: 100%;
            position: relative;
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        }
        .plan-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }
        .plan-card.featured {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        .plan-card.featured::before {
            content: 'Most Popular';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--primary);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 16px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .plan-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 18px;
        }
        .plan-icon.blue  { background: rgba(79,70,229,0.1); }
        .plan-icon.green { background: rgba(16,185,129,0.1); }
        .plan-icon.gray  { background: rgba(100,116,139,0.1); }
        .plan-title { font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 10px; }
        .plan-desc  { font-size: 0.88rem; color: var(--muted); line-height: 1.65; margin-bottom: 22px; }
        .plan-features { list-style: none; padding: 0; margin-bottom: 28px; }
        .plan-features li {
            font-size: 0.87rem;
            color: var(--text);
            padding: 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .plan-features li::before { content: '✓'; color: var(--secondary); font-weight: 700; }
        .plan-features li.dim::before { content: '—'; color: var(--muted); }
        .plan-features li.dim { color: var(--muted); }
        .btn-plan {
            display: block;
            text-align: center;
            padding: 12px 20px;
            border-radius: 9px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-plan-primary { background: var(--primary); color: #fff; }
        .btn-plan-primary:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); }
        .btn-plan-outline { border: 2px solid var(--primary); color: var(--primary); background: transparent; }
        .btn-plan-outline:hover { background: var(--primary); color: #fff; }
        .btn-plan-disabled { background: var(--light-bg); color: var(--muted); border: 1px solid var(--border); cursor: not-allowed; }

        /* ===== ARCHITECTURE ===== */
        #architecture { background: #fff; }
        .arch-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; }
        .arch-item {
            background: var(--light-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 26px 22px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .arch-item:hover { border-color: var(--primary); box-shadow: var(--shadow); }
        .arch-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .badge-os  { background: #fef3c7; color: #92400e; }
        .badge-web { background: #dbeafe; color: #1d4ed8; }
        .badge-sec { background: #fce7f3; color: #9d174d; }
        .badge-db  { background: #d1fae5; color: #065f46; }
        .arch-item-title { font-size: 1rem; font-weight: 600; color: var(--dark); margin-bottom: 8px; }
        .arch-item-desc  { font-size: 0.85rem; color: var(--muted); line-height: 1.65; }

        /* ===== TEAM ===== */
        #team { background: var(--light-bg); }
        .member-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px 24px;
            height: 100%;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .member-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-hover); }
        .member-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin: 0 auto 16px;
        }
        .member-name { font-size: 1.05rem; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
        .member-role {
            display: inline-block;
            background: rgba(79,70,229,0.1);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .member-reg { font-size: 0.82rem; color: var(--muted); margin-bottom: 16px; }
        .member-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.83rem;
            font-weight: 500;
            color: var(--secondary);
            text-decoration: none;
            border: 1px solid rgba(16,185,129,0.3);
            padding: 6px 14px;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
            word-break: break-all;
        }
        .member-link:hover { background: var(--secondary); color: #fff; }
        .alert-muted {
            background: var(--light-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px 28px;
            text-align: center;
            color: var(--muted);
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--dark);
            padding: 52px 0 28px;
        }
        .footer-brand { font-size: 1.4rem; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 10px; }
        .footer-brand span { color: var(--secondary); }
        .footer-tagline { font-size: 0.85rem; color: rgba(255,255,255,0.4); line-height: 1.65; }
        .footer-links { display: flex; gap: 24px; flex-wrap: wrap; }
        .footer-link { color: rgba(255,255,255,0.45); text-decoration: none; font-size: 0.85rem; transition: color 0.2s; }
        .footer-link:hover { color: #fff; }
        hr.footer-divider { border-color: rgba(255,255,255,0.08); margin: 36px 0 20px; }
        .footer-copy { font-size: 0.82rem; color: rgba(255,255,255,0.3); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .hero { padding: 70px 0 60px; }
            .hero-visual { min-height: 220px; margin-top: 40px; }
            .stat-item { border-right: none; padding: 10px 0; }
        }
        @media (max-width: 767px) {
            section { padding: 56px 0; }
            .hero-title { font-size: 2rem; }
            .footer-links { justify-content: center; }
            .footer-brand, .footer-tagline { text-align: center; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Mensa<span>Host</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item"><a class="nav-link" href="#plans">Plans</a></li>
                <li class="nav-item"><a class="nav-link" href="#architecture">Stack</a></li>
                <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link nav-cta" href="contact.php">Get Started</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- Left: copy + CTAs -->
            <div class="col-lg-6">
                <div class="hero-badge">Student-Run &middot; BBC MENSA &middot; MUBS</div>
                <h1 class="hero-title">Smart Hosting.<br><span>Zero Limits.</span></h1>
                <p class="hero-sub">
                    MensaHost is a student-operated web hosting company running a production-grade VPS
                    on Rocky Linux 9 — built entirely from the command line for the Web Server Administration
                    course at Makerere University Business School.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#plans" class="btn-hero-primary" onclick="smoothTo(event,'plans')">View Plans</a>
                    <a href="#team"  class="btn-hero-outline" onclick="smoothTo(event,'team')">Meet the Team</a>
                </div>
            </div>

            <!-- Right: illustration placeholder -->
            <div class="col-lg-6">
                <div class="hero-visual">
                    <span class="badge-float top-left">🔒 SSL Ready</span>
                    <div class="hero-icon-wrap">
                        <span class="hero-server-icon">🖥️</span>
                        <p class="hero-icon-label">mensahost.tech</p>
                    </div>
                    <span class="badge-float bottom-right">⚡ Rocky Linux 9</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== STATS STRIP ===== -->
<div class="stats-strip">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 g-0">
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">🌐</div>
                    <div>
                        <div class="stat-number">9+</div>
                        <div class="stat-label">Active Subdomains</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">🖥️</div>
                    <div>
                        <div class="stat-number">1 VPS</div>
                        <div class="stat-label">Rocky Linux 9</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">🛡️</div>
                    <div>
                        <div class="stat-number">UFW</div>
                        <div class="stat-label">Firewall + Fail2Ban</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">🔐</div>
                    <div>
                        <div class="stat-number">SSL</div>
                        <div class="stat-label">Let's Encrypt HTTPS</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== PLANS ===== -->
<section id="plans">
    <div class="container">
        <div class="text-center">
            <span class="section-badge">Hosting Plans</span>
            <h2 class="section-title">Choose Your Plan</h2>
            <p class="section-sub">Simple, transparent hosting options designed for students and small team projects.</p>
        </div>

        <div class="row g-4">

            <!-- Plan 1: Student Portfolio -->
            <div class="col-md-4">
                <div class="plan-card">
                    <div class="plan-icon blue">🎓</div>
                    <div class="plan-title">Student Portfolio Hosting</div>
                    <p class="plan-desc">Perfect for showcasing your personal projects, CV site, or academic portfolio on your own subdomain.</p>
                    <ul class="plan-features">
                        <li>Personal subdomain</li>
                        <li>Apache virtual host</li>
                        <li>SSL certificate included</li>
                        <li>1 GB web storage</li>
                    </ul>
                    <a href="contact.php" class="btn-plan btn-plan-outline">Get Started</a>
                </div>
            </div>

            <!-- Plan 2: Team Project (Featured) -->
            <div class="col-md-4">
                <div class="plan-card featured">
                    <div class="plan-icon green">👥</div>
                    <div class="plan-title">Team Project Hosting</div>
                    <p class="plan-desc">Host group assignments, PHP web apps, and collaborative university projects with shared DB access.</p>
                    <ul class="plan-features">
                        <li>Shared team subdomain</li>
                        <li>MySQL database access</li>
                        <li>PHP 8 + Apache</li>
                        <li>FTP file deployment</li>
                    </ul>
                    <a href="contact.php" class="btn-plan btn-plan-primary">Get Started</a>
                </div>
            </div>

            <!-- Plan 3: Coming Soon -->
            <div class="col-md-4">
                <div class="plan-card">
                    <div class="plan-icon gray">🔜</div>
                    <div class="plan-title">Future Clients <small style="font-size:0.7rem;color:var(--muted);">(Coming Soon)</small></div>
                    <p class="plan-desc">We're expanding. External client hosting will be available after the project concludes in Phase II.</p>
                    <ul class="plan-features">
                        <li>Custom domain support</li>
                        <li class="dim">CWP control panel</li>
                        <li class="dim">Email hosting (Postfix)</li>
                        <li class="dim">Priority support</li>
                    </ul>
                    <span class="btn-plan btn-plan-disabled">Coming Soon</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===== ARCHITECTURE ===== -->
<section id="architecture">
    <div class="container">
        <div class="text-center">
            <span class="section-badge">Technology Stack</span>
            <h2 class="section-title">Built on Solid Infrastructure</h2>
            <p class="section-sub">
                Every component was configured by hand from the command line — no control panels used in Phase I.
            </p>
        </div>

        <div class="arch-grid">
            <div class="arch-item">
                <span class="arch-badge badge-os">Operating System</span>
                <div class="arch-item-title">🐧 Rocky Linux 9</div>
                <p class="arch-item-desc">Enterprise-grade Linux based on RHEL. Chosen for stability, long-term support, and real-world relevance in server administration.</p>
            </div>
            <div class="arch-item">
                <span class="arch-badge badge-web">Web Server</span>
                <div class="arch-item-title">🌐 Apache 2.4</div>
                <p class="arch-item-desc">Virtual hosts configured for the main domain and each member's subdomain. Serves PHP via mod_php with custom DocumentRoot per site.</p>
            </div>
            <div class="arch-item">
                <span class="arch-badge badge-sec">Security</span>
                <div class="arch-item-title">🛡️ UFW + Fail2Ban</div>
                <p class="arch-item-desc">UFW firewall with deny-all default policy. Fail2Ban automatically blocks brute-force SSH and HTTP attacks with configurable ban rules.</p>
            </div>
            <div class="arch-item">
                <span class="arch-badge badge-db">Database</span>
                <div class="arch-item-title">🗄️ MySQL + PHP PDO</div>
                <p class="arch-item-desc">MySQL powers the dynamic team listings on this page. PDO with prepared statements ensures all queries are safe from SQL injection.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== TEAM (DYNAMIC FROM DATABASE) ===== -->
<section id="team">
    <div class="container">
        <div class="text-center">
            <span class="section-badge">Our Team</span>
            <h2 class="section-title">Meet the MensaHost Team</h2>
            <p class="section-sub">Each member manages their own subdomain on our shared VPS — from DNS configuration to file deployment.</p>
        </div>

        <?php if ($db_error): ?>
            <!-- DB query failed — show friendly message, details logged server-side only -->
            <div class="alert-muted">
                <p style="font-size:2.2rem;margin-bottom:14px;">⚠️</p>
                <p style="font-weight:600;color:var(--dark);margin-bottom:6px;">Team data temporarily unavailable</p>
                <p style="font-size:0.9rem;">We could not load the team information right now. Please try again shortly.</p>
            </div>

        <?php elseif (empty($members)): ?>
            <div class="alert-muted">
                <p style="font-size:2.2rem;margin-bottom:14px;">👥</p>
                <p style="font-weight:600;color:var(--dark);">No team members found in the database yet.</p>
            </div>

        <?php else: ?>
            <!-- 3. Loop over members and render each as a card -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($members as $member): ?>
                    <?php
                        // Build avatar initial from the member's name
                        $name    = htmlspecialchars($member['name'],                ENT_QUOTES, 'UTF-8');
                        $reg     = htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8');
                        $role    = htmlspecialchars($member['role'],                ENT_QUOTES, 'UTF-8');
                        $sub     = htmlspecialchars($member['subdomain'],           ENT_QUOTES, 'UTF-8');
                        $initial = mb_strtoupper(mb_substr($name, 0, 1));
                    ?>
                    <div class="col">
                        <div class="member-card">
                            <div class="member-avatar"><?= $initial ?></div>
                            <div class="member-name"><?= $name ?></div>
                            <span class="member-role"><?= $role ?></span>
                            <p class="member-reg">Reg No: <?= $reg ?></p>
                            <a href="https://<?= $sub ?>" class="member-link" target="_blank" rel="noopener noreferrer">
                                🔗 <?= $sub ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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
                    <a href="#team" class="footer-link" onclick="smoothTo(event,'team')">Our Team</a>
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

<!-- Bootstrap JS bundle (required for navbar collapse on mobile) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Smooth scroll helper for anchor buttons -->
<script>
function smoothTo(e, id) {
    e.preventDefault();
    var el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
}
</script>

</body>
</html>
