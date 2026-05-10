<?php
require_once __DIR__ . '/../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Kyarikunda Bakeine Grace']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Kyarikunda profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyarikunda Bakeine Grace &mdash; MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--primary:#7c3aed;--primary-dark:#6d28d9;--secondary:#10B981;--dark:#0f172a;--text:#1e293b;--muted:#64748b;--light-bg:#faf5ff;--border:#ede9fe;--radius:12px;--shadow:0 4px 20px rgba(0,0,0,0.08);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',system-ui,sans-serif;color:var(--text);background:#fff;}
        .navbar{background:rgba(255,255,255,0.97);border-bottom:1px solid var(--border);padding:14px 0;position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.3rem;font-weight:800;color:#4F46E5!important;}
        .navbar-brand span{color:var(--secondary);}
        .back-link{font-size:0.88rem;font-weight:500;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;}
        .back-link:hover{color:var(--primary);}
        .hero{background:linear-gradient(135deg,#2e1065 0%,#4c1d95 50%,#7c3aed 100%);padding:80px 0 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:450px;height:450px;background:radial-gradient(circle,rgba(196,181,253,0.2) 0%,transparent 70%);top:-60px;right:-60px;border-radius:50%;}
        .hero-avatar{width:110px;height:110px;background:linear-gradient(135deg,#7c3aed,#c084fc);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;margin:0 auto 24px;border:4px solid rgba(255,255,255,0.15);}
        .hero-badge{display:inline-block;background:rgba(196,181,253,0.2);color:#c084fc;font-size:0.78rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
        .hero-name{font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;}
        .hero-role{font-size:1.1rem;color:#c084fc;font-weight:600;margin-bottom:14px;}
        .hero-reg{font-size:0.88rem;color:rgba(255,255,255,0.5);margin-bottom:28px;}
        .hero-sub-link{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);color:#fff;font-size:0.9rem;font-weight:500;padding:10px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);transition:background 0.2s;}
        .hero-sub-link:hover{background:rgba(255,255,255,0.2);color:#fff;}
        section{padding:72px 0;}
        .section-badge{display:inline-block;background:rgba(124,58,237,0.1);color:var(--primary);font-size:0.75rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:12px;}
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
        <div class="hero-avatar">G</div>
        <div class="hero-badge">&#127912; UI/UX Designer</div>
        <h1 class="hero-name">Kyarikunda Bakeine Grace</h1>
        <p class="hero-role">UI/UX Designer</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="https://grace.mensahost.tech" class="hero-sub-link">&#127760; grace.mensahost.tech</a>
    </div>
</section>

<section>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge">About</span>
                <h2 class="section-title">Designing the MensaHost Experience</h2>
                <p class="section-lead">
                    Grace led the visual identity and user experience design of the MensaHost landing page. She
                    translated the team's technical infrastructure into an engaging, professional interface that
                    communicates trust and competence. Her designs ensure MensaHost looks as polished as any
                    commercial hosting provider, while remaining clean and maintainable by the team.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128444;</span>
                            <div class="contrib-title">Landing Page Layout</div>
                            <p class="contrib-desc">Designed the complete single-page layout for mensahost.tech &mdash; hero section, stats strip, plans grid, architecture section, and dynamic team cards.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#127912;</span>
                            <div class="contrib-title">Visual Identity &amp; Color System</div>
                            <p class="contrib-desc">Established the color palette (indigo #4F46E5 + emerald #10B981), selected Poppins as the brand typeface, and defined all CSS custom properties used across the site.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128241;</span>
                            <div class="contrib-title">Responsive Design</div>
                            <p class="contrib-desc">Ensured all layouts work at mobile (320px+), tablet (768px+), and desktop breakpoints using Bootstrap 5 grid and custom CSS media queries.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#10024;</span>
                            <div class="contrib-title">Hover &amp; Interaction States</div>
                            <p class="contrib-desc">Designed hover lift effects on cards, smooth button transitions, floating badge decorations on the hero, and the gradient visual placeholder illustration.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="background:var(--light-bg);">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <span class="section-badge">Design Decisions</span>
                <h2 class="section-title" style="font-size:1.7rem;">Key Design Choices</h2>
                <div class="mt-4" style="display:flex;flex-direction:column;gap:20px;">
                    <div>
                        <p style="font-size:0.95rem;font-weight:600;color:var(--dark);margin-bottom:6px;">Why Poppins?</p>
                        <p style="font-size:0.87rem;color:var(--muted);line-height:1.65;">A geometric sans-serif with excellent legibility at all sizes. Its rounded letterforms convey approachability while bold weights create strong visual hierarchy &mdash; ideal for a student tech company targeting academics.</p>
                    </div>
                    <div>
                        <p style="font-size:0.95rem;font-weight:600;color:var(--dark);margin-bottom:6px;">Hero Gradient Strategy</p>
                        <p style="font-size:0.87rem;color:var(--muted);line-height:1.65;">Dark navy-to-indigo signals professionalism and trust. The emerald accent creates high contrast on CTAs, guiding users toward the key actions on the page without visual clutter.</p>
                    </div>
                    <div>
                        <p style="font-size:0.95rem;font-weight:600;color:var(--dark);margin-bottom:6px;">Card Elevation Pattern</p>
                        <p style="font-size:0.87rem;color:var(--muted);line-height:1.65;">Cards rest flat by default and lift on hover (translateY + increased shadow). This provides satisfying feedback that helps users identify interactive elements without relying on colour alone.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <span class="section-badge">Skills &amp; Tools</span>
                <h2 class="section-title" style="font-size:1.7rem;">Technical Stack</h2>
                <div class="mt-4">
                    <span class="skill-tag">HTML5</span>
                    <span class="skill-tag">CSS3</span>
                    <span class="skill-tag">Bootstrap 5</span>
                    <span class="skill-tag">CSS Custom Properties</span>
                    <span class="skill-tag">Flexbox &amp; Grid</span>
                    <span class="skill-tag">Google Fonts</span>
                    <span class="skill-tag">Responsive Design</span>
                    <span class="skill-tag">CSS Transitions</span>
                    <span class="skill-tag">UI/UX Principles</span>
                    <span class="skill-tag">Color Theory</span>
                    <span class="skill-tag">Typography</span>
                    <span class="skill-tag">Visual Hierarchy</span>
                </div>
            </div>
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
