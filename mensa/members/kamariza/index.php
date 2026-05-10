<?php
require_once __DIR__ . '/../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Kamariza Hellena']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Kamariza profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamariza Hellena &mdash; MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--primary:#db2777;--primary-dark:#be185d;--secondary:#10B981;--dark:#0f172a;--text:#1e293b;--muted:#64748b;--light-bg:#fdf2f8;--border:#fbcfe8;--radius:12px;--shadow:0 4px 20px rgba(0,0,0,0.08);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',system-ui,sans-serif;color:var(--text);background:#fff;}
        .navbar{background:rgba(255,255,255,0.97);border-bottom:1px solid var(--border);padding:14px 0;position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.3rem;font-weight:800;color:#4F46E5!important;}
        .navbar-brand span{color:var(--secondary);}
        .back-link{font-size:0.88rem;font-weight:500;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;}
        .back-link:hover{color:var(--primary);}
        .hero{background:linear-gradient(135deg,#500724 0%,#831843 50%,#db2777 100%);padding:80px 0 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:450px;height:450px;background:radial-gradient(circle,rgba(251,207,232,0.2) 0%,transparent 70%);top:-60px;right:-60px;border-radius:50%;}
        .hero-avatar{width:110px;height:110px;background:linear-gradient(135deg,#db2777,#f472b6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;margin:0 auto 24px;border:4px solid rgba(255,255,255,0.15);}
        .hero-badge{display:inline-block;background:rgba(251,207,232,0.2);color:#f9a8d4;font-size:0.78rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
        .hero-name{font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;}
        .hero-role{font-size:1.1rem;color:#f9a8d4;font-weight:600;margin-bottom:14px;}
        .hero-reg{font-size:0.88rem;color:rgba(255,255,255,0.5);margin-bottom:28px;}
        .hero-sub-link{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);color:#fff;font-size:0.9rem;font-weight:500;padding:10px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);transition:background 0.2s;}
        .hero-sub-link:hover{background:rgba(255,255,255,0.2);color:#fff;}
        section{padding:72px 0;}
        .section-badge{display:inline-block;background:rgba(219,39,119,0.1);color:var(--primary);font-size:0.75rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:12px;}
        .section-title{font-size:1.9rem;font-weight:700;color:var(--dark);letter-spacing:-0.4px;margin-bottom:14px;}
        .section-lead{font-size:1rem;color:var(--muted);line-height:1.75;}
        .contrib-card{background:var(--light-bg);border:1px solid var(--border);border-radius:var(--radius);padding:28px 24px;height:100%;transition:border-color 0.2s,box-shadow 0.2s;}
        .contrib-card:hover{border-color:var(--primary);box-shadow:var(--shadow);}
        .contrib-icon{font-size:2rem;margin-bottom:14px;display:block;}
        .contrib-title{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:8px;}
        .contrib-desc{font-size:0.87rem;color:var(--muted);line-height:1.65;}
        .skill-tag{display:inline-block;background:#fff;border:1px solid var(--border);color:var(--text);font-size:0.83rem;font-weight:500;padding:6px 16px;border-radius:8px;margin:4px;transition:border-color 0.2s,color 0.2s;}
        .skill-tag:hover{border-color:var(--primary);color:var(--primary);}
        footer{background:var(--dark);padding:36px 0 24px;}
        .footer-brand{font-size:1.2rem;font-weight:800;color:#fff;}
        .footer-brand span{color:var(--secondary);}
        .footer-copy{font-size:0.8rem;color:rgba(255,255,255,0.3);margin-top:8px;}
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="https://mensahost.tech">Mensa<span>Host</span></a>
        <a href="https://mensahost.tech#team" class="back-link">&#8592; Back to Team</a>
    </div>
</nav>

<section class="hero">
    <div class="container text-center" style="position:relative;z-index:1;">
        <div class="hero-avatar">H</div>
        <div class="hero-badge">&#127912; UI/UX Designer</div>
        <h1 class="hero-name">Kamariza Hellena</h1>
        <p class="hero-role">UI/UX Designer</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="https://hellena.mensahost.tech" class="hero-sub-link">&#127760; hellena.mensahost.tech</a>
    </div>
</section>

<section>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge">About</span>
                <h2 class="section-title">Building the Frontend Interface</h2>
                <p class="section-lead">
                    Hellena co-led the frontend design of MensaHost, focusing on component-level implementation
                    and CSS precision. Working alongside the lead designer, she translated wireframes into
                    pixel-perfect HTML and CSS &mdash; handling the plans section, team cards, architecture grid,
                    and ensuring visual consistency across all nine member subdomain pages.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128179;</span>
                            <div class="contrib-title">Plans Section Design</div>
                            <p class="contrib-desc">Designed and built the three hosting plan cards with the featured badge, feature checklists, hover elevation effects, and responsive stacking on mobile screens.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128101;</span>
                            <div class="contrib-title">Team Member Cards</div>
                            <p class="contrib-desc">Built the dynamic team card grid with avatar initials, role badges, registration numbers, and subdomain links &mdash; all rendered from the database via PHP and PDO.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#127968;</span>
                            <div class="contrib-title">Architecture Section</div>
                            <p class="contrib-desc">Designed the technology stack grid with colour-coded category badges (OS, Web Server, Security, Database) and hover interactions to highlight each stack component.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128203;</span>
                            <div class="contrib-title">Subdomain Page Templates</div>
                            <p class="contrib-desc">Designed the member subdomain page template &mdash; navbar, hero, contribution cards, timeline, skills, and footer &mdash; adapted with unique colours for each member's role.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="background:var(--light-bg);">
    <div class="container">
        <span class="section-badge">Skills &amp; Tools</span>
        <h2 class="section-title" style="font-size:1.7rem;">Technical Stack</h2>
        <div class="mt-4">
            <span class="skill-tag">HTML5</span>
            <span class="skill-tag">CSS3</span>
            <span class="skill-tag">Bootstrap 5</span>
            <span class="skill-tag">CSS Flexbox</span>
            <span class="skill-tag">CSS Grid</span>
            <span class="skill-tag">PHP Templating</span>
            <span class="skill-tag">Responsive Design</span>
            <span class="skill-tag">CSS Variables</span>
            <span class="skill-tag">Component Design</span>
            <span class="skill-tag">Cross-Browser Compatibility</span>
            <span class="skill-tag">Google Fonts</span>
            <span class="skill-tag">Visual Consistency</span>
        </div>
    </div>
</section>

<footer>
    <div class="container text-center">
        <div class="footer-brand">Mensa<span>Host</span></div>
        <p class="footer-copy">&copy; <?= date('Y') ?> MensaHost &mdash; BUC3219 Web Server Administration, MUBS</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
