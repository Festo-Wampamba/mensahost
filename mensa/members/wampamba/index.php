<?php
require_once __DIR__ . '/../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Wampamba Festo']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Wampamba profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wampamba Festo &mdash; Team Leader, MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Playfair Display for headings + Poppins for body — luxury editorial pair -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ===== PREMIUM DARK GOLD SYSTEM ===== */
        :root {
            --gold:        #C9A84C;
            --gold-light:  #E8C96A;
            --gold-dim:    #8B6914;
            --copper:      #B87333;
            --dark:        #080C14;
            --dark-2:      #0D1320;
            --dark-3:      #111827;
            --surface:     #141C2B;
            --surface-2:   #1A2336;
            --text:        #F0EBE0;
            --text-muted:  #8A9AB8;
            --border:      rgba(201,168,76,0.18);
            --border-dim:  rgba(201,168,76,0.08);
            --radius:      14px;
            --glow:        0 0 40px rgba(201,168,76,0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            background: var(--dark);
            color: var(--text);
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', Georgia, serif;
        }

        /* ===== NOISE TEXTURE OVERLAY ===== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(8,12,20,0.94);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: #fff !important;
            letter-spacing: -0.5px;
        }
        .navbar-brand span { color: var(--gold); }
        .back-link {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--gold); }

        /* ===== HERO ===== */
        .hero {
            background: var(--dark);
            padding: 90px 0 80px;
            position: relative;
            overflow: hidden;
        }
        /* Large radial gold glow top-left */
        .hero::before {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(ellipse, rgba(201,168,76,0.10) 0%, transparent 65%);
            top: -200px; left: -200px;
            pointer-events: none;
        }
        /* Subtle grid lines */
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(201,168,76,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201,168,76,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }
        .hero-inner { position: relative; z-index: 1; }

        /* Avatar ring with gold gradient border */
        .hero-avatar-wrap {
            position: relative;
            width: 130px; height: 130px;
            margin: 0 auto 28px;
        }
        .hero-avatar-ring {
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            background: conic-gradient(var(--gold), var(--copper), var(--gold-light), var(--gold-dim), var(--gold));
            animation: spin 8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .hero-avatar {
            position: relative;
            width: 130px; height: 130px;
            background: linear-gradient(135deg, var(--surface-2), var(--dark-3));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 900;
            color: var(--gold);
            border: 3px solid var(--dark);
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.1);
            border: 1px solid var(--border);
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 30px;
            margin-bottom: 20px;
        }
        .hero-name {
            font-size: clamp(2.4rem, 5vw, 4rem);
            font-weight: 900;
            color: var(--text);
            letter-spacing: -1px;
            line-height: 1.1;
            margin-bottom: 10px;
        }
        .hero-name em {
            font-style: normal;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-role {
            font-size: 1.05rem;
            color: var(--text-muted);
            font-weight: 400;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .hero-reg {
            font-size: 0.82rem;
            color: var(--gold-dim);
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        /* Social links row */
        .social-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }
        .social-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }
        .social-btn-primary {
            background: linear-gradient(135deg, var(--gold), var(--copper));
            color: var(--dark);
            border: none;
        }
        .social-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201,168,76,0.35);
            color: var(--dark);
        }
        .social-btn-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }
        .social-btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: rgba(201,168,76,0.06);
        }

        /* ===== DIVIDER ===== */
        .gold-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0.3;
            margin: 0;
        }

        /* ===== SECTIONS ===== */
        section { padding: 80px 0; position: relative; }
        .section-label {
            display: inline-block;
            color: var(--gold);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(1.7rem, 3vw, 2.3rem);
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }
        .section-lead {
            font-size: 0.97rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        /* ===== CONTRIBUTION CARDS ===== */
        .contrib-card {
            background: var(--surface);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius);
            padding: 26px 22px;
            height: 100%;
            transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
            position: relative;
            overflow: hidden;
        }
        .contrib-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0;
            transition: opacity 0.25s;
        }
        .contrib-card:hover {
            border-color: var(--border);
            box-shadow: var(--glow);
            transform: translateY(-4px);
        }
        .contrib-card:hover::before { opacity: 1; }
        .contrib-icon { font-size: 1.8rem; margin-bottom: 14px; display: block; }
        .contrib-title { font-size: 0.95rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .contrib-desc { font-size: 0.84rem; color: var(--text-muted); line-height: 1.7; }

        /* ===== EXPERTISE SECTION ===== */
        #expertise { background: var(--dark-2); }
        .expertise-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .expertise-item {
            background: var(--surface);
            border: 1px solid var(--border-dim);
            border-radius: 10px;
            padding: 20px 18px;
            transition: border-color 0.2s, transform 0.2s;
            cursor: default;
        }
        .expertise-item:hover { border-color: var(--gold); transform: translateY(-3px); }
        .exp-icon { font-size: 1.5rem; margin-bottom: 10px; display: block; }
        .exp-title { font-size: 0.88rem; font-weight: 700; color: var(--text); margin-bottom: 4px; }
        .exp-level {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .exp-bar {
            flex: 1;
            height: 3px;
            background: var(--surface-2);
            border-radius: 2px;
            overflow: hidden;
        }
        .exp-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--gold-dim), var(--gold));
            border-radius: 2px;
        }
        .exp-pct { font-size: 0.72rem; color: var(--gold); font-weight: 600; }
        .exp-desc { font-size: 0.78rem; color: var(--text-muted); line-height: 1.55; }

        /* ===== SCROLLABLE TIMELINE ===== */
        #timeline-section { background: var(--dark-3); }
        .timeline-scroll {
            max-height: 380px;
            overflow-y: auto;
            padding-right: 8px;
            /* Custom scrollbar */
            scrollbar-width: thin;
            scrollbar-color: var(--gold-dim) var(--surface);
        }
        .timeline-scroll::-webkit-scrollbar { width: 4px; }
        .timeline-scroll::-webkit-scrollbar-track { background: var(--surface); border-radius: 2px; }
        .timeline-scroll::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 2px; }
        .timeline-scroll::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        .timeline { position: relative; padding-left: 30px; }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px; top: 0; bottom: 0;
            width: 1px;
            background: linear-gradient(to bottom, var(--gold), rgba(201,168,76,0.1));
        }
        .tl-item { position: relative; padding-bottom: 28px; }
        .tl-item:last-child { padding-bottom: 4px; }
        .tl-item::before {
            content: '';
            position: absolute;
            left: -26px; top: 4px;
            width: 14px; height: 14px;
            border-radius: 50%;
            background: var(--dark-3);
            border: 2px solid var(--gold);
            box-shadow: 0 0 10px rgba(201,168,76,0.4);
        }
        .tl-phase {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 4px;
        }
        .tl-title { font-size: 0.92rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
        .tl-desc { font-size: 0.82rem; color: var(--text-muted); line-height: 1.65; }
        .tl-desc code {
            background: rgba(201,168,76,0.1);
            color: var(--gold);
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        /* Scroll hint */
        .scroll-hint {
            text-align: center;
            font-size: 0.75rem;
            color: var(--gold-dim);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 12px;
            opacity: 0.8;
        }

        /* ===== SKILLS SECTION ===== */
        #skills { background: var(--dark-2); }
        .skills-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 14px; }
        .skill-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .skill-info { display: flex; justify-content: space-between; align-items: center; }
        .skill-name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .skill-pct  { font-size: 0.78rem; font-weight: 600; color: var(--gold); }
        .skill-bar  { height: 5px; background: var(--surface-2); border-radius: 3px; overflow: hidden; }
        .skill-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--gold-dim), var(--gold-light));
            border-radius: 3px;
            transition: width 1s ease;
        }

        /* Skill tags */
        .skill-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 32px; }
        .stag {
            display: inline-block;
            background: var(--surface);
            border: 1px solid var(--border-dim);
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            transition: border-color 0.2s, color 0.2s;
        }
        .stag:hover { border-color: var(--gold); color: var(--gold); }

        /* ===== TECH STACK (in timeline section right col) ===== */
        .tech-tag {
            display: inline-block;
            background: var(--surface);
            border: 1px solid var(--border-dim);
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            margin: 4px;
            transition: border-color 0.2s, color 0.2s;
        }
        .tech-tag:hover { border-color: var(--gold); color: var(--gold); }

        /* ===== FOOTER ===== */
        footer {
            background: var(--dark);
            border-top: 1px solid var(--border-dim);
            padding: 36px 0 24px;
        }
        .footer-brand { font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 800; color: #fff; }
        .footer-brand span { color: var(--gold); }
        .footer-copy { font-size: 0.8rem; color: var(--text-muted); margin-top: 8px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 767px) {
            .hero { padding: 60px 0 50px; }
            section { padding: 56px 0; }
            .hero-name { font-size: 2.2rem; }
            .social-btn { padding: 9px 16px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="https://mensahost.tech">Mensa<span>Host</span></a>
        <a href="https://mensahost.tech#team" class="back-link">&#8592; Back to Team</a>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container hero-inner text-center">

        <!-- Spinning gold avatar -->
        <div class="hero-avatar-wrap">
            <div class="hero-avatar-ring"></div>
            <div class="hero-avatar">W</div>
        </div>

        <div class="hero-badge">&#9813; Team Leader &amp; Admin</div>
        <h1 class="hero-name">Wampamba <em>Festo</em></h1>
        <p class="hero-role">Team Leader &bull; DevOps Engineer &bull; Full-Stack Developer</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <!-- Social / external links -->
        <div class="social-links">
            <a href="https://festo.mensahost.tech" class="social-btn social-btn-primary" target="_blank" rel="noopener">
                &#127760; My Website
            </a>
            <a href="https://github.com/Festo-Wampamba" class="social-btn social-btn-outline" target="_blank" rel="noopener">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                GitHub
            </a>
            <a href="https://www.linkedin.com/in/festoug/" class="social-btn social-btn-outline" target="_blank" rel="noopener">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                LinkedIn
            </a>
        </div>

    </div>
</section>

<div class="gold-divider"></div>

<!-- ===== ABOUT + CONTRIBUTIONS ===== -->
<section style="background:var(--dark);">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-5">
                <span class="section-label">About</span>
                <h2 class="section-title">Leading MensaHost<br>from Ground Up</h2>
                <p class="section-lead">
                    As Team Leader and DevOps Engineer, Festo is responsible for the entire MensaHost
                    infrastructure &mdash; from provisioning the VPS and configuring DNS to orchestrating
                    the deployment pipeline used by every team member. He combines server administration
                    expertise with full-stack development skills to keep the project cohesive, secure, and on time.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128187;</span>
                            <div class="contrib-title">VPS Provisioning</div>
                            <p class="contrib-desc">Acquired and initialised the Rocky Linux 9 VPS, configured SSH key authentication, created the admin sudo user, and hardened direct root login.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#127760;</span>
                            <div class="contrib-title">Domain &amp; DNS</div>
                            <p class="contrib-desc">Registered mensahost.tech, added it to Cloudflare, and created A records for the root domain, www, and all nine member subdomains.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128640;</span>
                            <div class="contrib-title">Deployment Pipeline</div>
                            <p class="contrib-desc">Set up vsftpd with individual chroot FTP accounts for each member and deployed all source files from GitHub to each document root.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128203;</span>
                            <div class="contrib-title">Team Coordination</div>
                            <p class="contrib-desc">Assigned roles, tracked milestones, resolved blockers, and ensured all deliverables were complete before the submission deadline.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="gold-divider"></div>

<!-- ===== EXPERTISE ===== -->
<section id="expertise">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Expertise</span>
            <h2 class="section-title">Areas of Mastery</h2>
            <p class="section-lead" style="max-width:520px;margin:0 auto;">
                Beyond the BUC project, Festo brings hands-on experience across the full software and
                infrastructure stack &mdash; from writing production PHP and JavaScript to hardening Linux servers.
            </p>
        </div>
        <div class="expertise-grid">
            <div class="expertise-item">
                <span class="exp-icon">&#9881;</span>
                <div class="exp-title">Linux Server Admin</div>
                <div class="exp-level">
                    <div class="exp-bar"><div class="exp-fill" style="width:92%;"></div></div>
                    <span class="exp-pct">92%</span>
                </div>
                <p class="exp-desc">Rocky Linux / Ubuntu VPS setup, systemd, user management, cron jobs, log analysis.</p>
            </div>
            <div class="expertise-item">
                <span class="exp-icon">&#128640;</span>
                <div class="exp-title">DevOps &amp; CI/CD</div>
                <div class="exp-level">
                    <div class="exp-bar"><div class="exp-fill" style="width:80%;"></div></div>
                    <span class="exp-pct">80%</span>
                </div>
                <p class="exp-desc">Git workflows, FTP/SFTP deployment, Apache virtual host automation, environment configuration.</p>
            </div>
            <div class="expertise-item">
                <span class="exp-icon">&#128218;</span>
                <div class="exp-title">Full-Stack PHP/JS</div>
                <div class="exp-level">
                    <div class="exp-bar"><div class="exp-fill" style="width:85%;"></div></div>
                    <span class="exp-pct">85%</span>
                </div>
                <p class="exp-desc">PHP 8 with PDO, MySQL, REST APIs, HTML5/CSS3, Bootstrap, vanilla JavaScript.</p>
            </div>
            <div class="expertise-item">
                <span class="exp-icon">&#128737;</span>
                <div class="exp-title">Network Security</div>
                <div class="exp-level">
                    <div class="exp-bar"><div class="exp-fill" style="width:78%;"></div></div>
                    <span class="exp-pct">78%</span>
                </div>
                <p class="exp-desc">UFW firewall rules, Fail2Ban, SSL/TLS with Certbot, SSH hardening, port auditing.</p>
            </div>
            <div class="expertise-item">
                <span class="exp-icon">&#128451;</span>
                <div class="exp-title">Database Design</div>
                <div class="exp-level">
                    <div class="exp-bar"><div class="exp-fill" style="width:75%;"></div></div>
                    <span class="exp-pct">75%</span>
                </div>
                <p class="exp-desc">MySQL schema design, user provisioning, PDO prepared statements, backup strategies.</p>
            </div>
            <div class="expertise-item">
                <span class="exp-icon">&#127760;</span>
                <div class="exp-title">DNS &amp; Web Hosting</div>
                <div class="exp-level">
                    <div class="exp-bar"><div class="exp-fill" style="width:88%;"></div></div>
                    <span class="exp-pct">88%</span>
                </div>
                <p class="exp-desc">Cloudflare DNS management, A records, subdomains, SSL proxying, Apache vhosts.</p>
            </div>
        </div>
    </div>
</section>

<div class="gold-divider"></div>

<!-- ===== TIMELINE + TECH STACK ===== -->
<section id="timeline-section">
    <div class="container">
        <div class="row g-5">

            <!-- Scrollable Timeline -->
            <div class="col-lg-6">
                <span class="section-label">Project Timeline</span>
                <h2 class="section-title" style="font-size:1.7rem;">Key Milestones</h2>

                <div class="timeline-scroll mt-4">
                    <div class="timeline">
                        <div class="tl-item">
                            <div class="tl-phase">Phase 1</div>
                            <div class="tl-title">VPS Acquisition &amp; Initial Setup</div>
                            <p class="tl-desc">Provisioned Rocky Linux 9 VPS, set hostname to <code>mensahost</code>, updated all system packages with <code>dnf update -y</code>, configured timezone to Africa/Kampala.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 2</div>
                            <div class="tl-title">SSH Hardening &amp; User Management</div>
                            <p class="tl-desc">Generated SSH key pair, configured key-based authentication, disabled <code>PasswordAuthentication</code> and <code>PermitRootLogin</code> in sshd_config. Created non-root sudo admin.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 3</div>
                            <div class="tl-title">Apache Install &amp; Virtual Host Config</div>
                            <p class="tl-desc">Installed Apache 2.4, created virtual host configs for <code>mensahost.tech</code> and all nine member subdomains under <code>/var/www/</code>. Set <code>chown</code> and <code>chmod</code> correctly per vhost.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 4</div>
                            <div class="tl-title">DNS Registration &amp; Cloudflare Setup</div>
                            <p class="tl-desc">Registered mensahost.tech, pointed nameservers to Cloudflare, created A records for root domain, www, and all nine member subdomains with the VPS public IP.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 5</div>
                            <div class="tl-title">FTP Server &amp; Member Accounts</div>
                            <p class="tl-desc">Installed vsftpd, configured passive mode with port range, created individual chroot FTP accounts for each team member locked to their document root.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 6</div>
                            <div class="tl-title">MySQL &amp; PHP Setup</div>
                            <p class="tl-desc">Installed MySQL 8 and PHP 8, ran <code>mysql_secure_installation</code>, executed <code>schema.sql</code> to create <code>mensa_db</code>, provisioned <code>mensa_user</code> with scoped privileges.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 7</div>
                            <div class="tl-title">SSL Certificates via Certbot</div>
                            <p class="tl-desc">Installed Certbot, obtained Let's Encrypt certificates for all domains and subdomains. Configured HTTP-to-HTTPS redirect in Apache. Verified <code>certbot renew --dry-run</code>.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 8</div>
                            <div class="tl-title">GitHub Source &amp; File Deployment</div>
                            <p class="tl-desc">Published source files to GitHub, downloaded via <code>git clone</code> on server, deployed index.php and member pages to correct document roots via SFTP.</p>
                        </div>
                        <div class="tl-item">
                            <div class="tl-phase">Phase 9</div>
                            <div class="tl-title">Final Verification &amp; Submission</div>
                            <p class="tl-desc">Tested all 10 domains in browser over HTTPS, verified DB returns all members, confirmed report completeness. Submitted via MUBSEP before deadline.</p>
                        </div>
                    </div>
                </div>
                <p class="scroll-hint">&#9660; Scroll to view all milestones</p>
            </div>

            <!-- Tech Stack -->
            <div class="col-lg-6">
                <span class="section-label">Tech Stack</span>
                <h2 class="section-title" style="font-size:1.7rem;">Tools &amp; Technologies</h2>
                <div class="mt-4">
                    <span class="tech-tag">Rocky Linux 9</span>
                    <span class="tech-tag">Apache 2.4</span>
                    <span class="tech-tag">PHP 8</span>
                    <span class="tech-tag">MySQL 8</span>
                    <span class="tech-tag">PHP PDO</span>
                    <span class="tech-tag">SSH / OpenSSH</span>
                    <span class="tech-tag">Cloudflare DNS</span>
                    <span class="tech-tag">vsftpd / SFTP</span>
                    <span class="tech-tag">GitHub</span>
                    <span class="tech-tag">Certbot / Let's Encrypt</span>
                    <span class="tech-tag">UFW Firewall</span>
                    <span class="tech-tag">Fail2Ban</span>
                    <span class="tech-tag">Bash Scripting</span>
                    <span class="tech-tag">Linux User Mgmt</span>
                    <span class="tech-tag">Virtual Hosts</span>
                    <span class="tech-tag">HTML5 / CSS3</span>
                    <span class="tech-tag">Bootstrap 5</span>
                    <span class="tech-tag">JavaScript</span>
                    <span class="tech-tag">systemd</span>
                    <span class="tech-tag">dnf / yum</span>
                    <span class="tech-tag">FileZilla</span>
                    <span class="tech-tag">TigerVNC</span>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="gold-divider"></div>

<!-- ===== SKILLS ===== -->
<section id="skills">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Skills</span>
            <h2 class="section-title">Technical Proficiency</h2>
        </div>
        <div class="skills-grid">
            <div>
                <div class="skill-row mb-4">
                    <div class="skill-info"><span class="skill-name">Linux Server Administration</span><span class="skill-pct">92%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:92%;"></div></div>
                </div>
                <div class="skill-row mb-4">
                    <div class="skill-info"><span class="skill-name">Apache Web Server Config</span><span class="skill-pct">90%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:90%;"></div></div>
                </div>
                <div class="skill-row mb-4">
                    <div class="skill-info"><span class="skill-name">DNS &amp; Domain Management</span><span class="skill-pct">88%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:88%;"></div></div>
                </div>
                <div class="skill-row">
                    <div class="skill-info"><span class="skill-name">PHP / MySQL Development</span><span class="skill-pct">85%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:85%;"></div></div>
                </div>
            </div>
            <div>
                <div class="skill-row mb-4">
                    <div class="skill-info"><span class="skill-name">DevOps &amp; Deployment</span><span class="skill-pct">82%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:82%;"></div></div>
                </div>
                <div class="skill-row mb-4">
                    <div class="skill-info"><span class="skill-name">Network Security (UFW/Fail2Ban)</span><span class="skill-pct">80%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:80%;"></div></div>
                </div>
                <div class="skill-row mb-4">
                    <div class="skill-info"><span class="skill-name">SSL/TLS &amp; HTTPS</span><span class="skill-pct">88%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:88%;"></div></div>
                </div>
                <div class="skill-row">
                    <div class="skill-info"><span class="skill-name">Team Leadership</span><span class="skill-pct">90%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:90%;"></div></div>
                </div>
            </div>
        </div>

        <!-- Tag cloud -->
        <div class="skill-tags mt-2">
            <span class="stag">Bash</span>
            <span class="stag">Git &amp; GitHub</span>
            <span class="stag">HTML5</span>
            <span class="stag">CSS3</span>
            <span class="stag">JavaScript</span>
            <span class="stag">Bootstrap 5</span>
            <span class="stag">FileZilla / SFTP</span>
            <span class="stag">cron / crontab</span>
            <span class="stag">rsync</span>
            <span class="stag">mysqldump</span>
            <span class="stag">systemd</span>
            <span class="stag">TigerVNC</span>
            <span class="stag">Cloudflare</span>
            <span class="stag">Certbot</span>
            <span class="stag">Postfix</span>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
    <div class="container text-center">
        <div class="footer-brand">Mensa<span>Host</span></div>
        <p class="footer-copy">&copy; <?= date('Y') ?> MensaHost &mdash; BBC MENSA, Web Server Administration, MUBS</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
