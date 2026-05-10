<?php
require_once '../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Tumusiime Elvin Luke']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Tumusiime profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tumusiime Elvin Luke &mdash; MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--primary:#059669;--primary-dark:#047857;--secondary:#10B981;--dark:#0f172a;--text:#1e293b;--muted:#64748b;--light-bg:#f0fdf4;--border:#bbf7d0;--radius:12px;--shadow:0 4px 20px rgba(0,0,0,0.08);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',system-ui,sans-serif;color:var(--text);background:#fff;}
        .navbar{background:rgba(255,255,255,0.97);border-bottom:1px solid var(--border);padding:14px 0;position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.3rem;font-weight:800;color:#4F46E5!important;}
        .navbar-brand span{color:var(--secondary);}
        .back-link{font-size:0.88rem;font-weight:500;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;}
        .back-link:hover{color:var(--primary);}
        .hero{background:linear-gradient(135deg,#022c22 0%,#064e3b 50%,#059669 100%);padding:80px 0 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:450px;height:450px;background:radial-gradient(circle,rgba(52,211,153,0.2) 0%,transparent 70%);top:-60px;right:-60px;border-radius:50%;}
        .hero-avatar{width:110px;height:110px;background:linear-gradient(135deg,#059669,#34d399);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;margin:0 auto 24px;border:4px solid rgba(255,255,255,0.15);}
        .hero-badge{display:inline-block;background:rgba(52,211,153,0.2);color:#6ee7b7;font-size:0.78rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
        .hero-name{font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;}
        .hero-role{font-size:1.1rem;color:#6ee7b7;font-weight:600;margin-bottom:14px;}
        .hero-reg{font-size:0.88rem;color:rgba(255,255,255,0.5);margin-bottom:28px;}
        .hero-sub-link{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);color:#fff;font-size:0.9rem;font-weight:500;padding:10px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);transition:background 0.2s;}
        .hero-sub-link:hover{background:rgba(255,255,255,0.2);color:#fff;}
        section{padding:72px 0;}
        .section-badge{display:inline-block;background:rgba(5,150,105,0.1);color:var(--primary);font-size:0.75rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:12px;}
        .section-title{font-size:1.9rem;font-weight:700;color:var(--dark);letter-spacing:-0.4px;margin-bottom:14px;}
        .section-lead{font-size:1rem;color:var(--muted);line-height:1.75;}
        .contrib-card{background:var(--light-bg);border:1px solid var(--border);border-radius:var(--radius);padding:28px 24px;height:100%;transition:border-color 0.2s,box-shadow 0.2s;}
        .contrib-card:hover{border-color:var(--primary);box-shadow:var(--shadow);}
        .contrib-icon{font-size:2rem;margin-bottom:14px;display:block;}
        .contrib-title{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:8px;}
        .contrib-desc{font-size:0.87rem;color:var(--muted);line-height:1.65;}
        .timeline{position:relative;padding-left:28px;}
        .timeline::before{content:'';position:absolute;left:8px;top:0;bottom:0;width:2px;background:#bbf7d0;}
        .tl-item{position:relative;padding-bottom:28px;}
        .tl-item::before{content:'';position:absolute;left:-24px;top:5px;width:12px;height:12px;border-radius:50%;background:var(--primary);border:2px solid #fff;box-shadow:0 0 0 2px var(--primary);}
        .tl-title{font-size:0.95rem;font-weight:600;color:var(--dark);margin-bottom:4px;}
        .tl-desc{font-size:0.85rem;color:var(--muted);line-height:1.6;}
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
        <div class="hero-avatar">E</div>
        <div class="hero-badge">&#128451; Database Management</div>
        <h1 class="hero-name">Tumusiime Elvin Luke</h1>
        <p class="hero-role">Database Administrator</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="https://elvin.mensahost.tech" class="hero-sub-link">&#127760; elvin.mensahost.tech</a>
    </div>
</section>

<section>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge">About</span>
                <h2 class="section-title">Powering MensaHost with MySQL</h2>
                <p class="section-lead">
                    Elvin designed and implemented the MySQL database that powers the dynamic team listings on
                    the MensaHost landing page. He built the schema, created the dedicated database user, populated
                    the members table, and validated the PDO queries in the PHP code &mdash; ensuring data is
                    served correctly and securely on every page load.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128196;</span>
                            <div class="contrib-title">Schema Design (schema.sql)</div>
                            <p class="contrib-desc">Wrote schema.sql to create mensa_db, provision mensa_user with least-privilege GRANT, and define the members table with appropriate VARCHAR lengths and AUTO_INCREMENT primary key.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128101;</span>
                            <div class="contrib-title">Data Population</div>
                            <p class="contrib-desc">Inserted all nine team members with their correct full names, registration numbers, roles, and subdomain assignments using SQL INSERT statements in schema.sql.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128274;</span>
                            <div class="contrib-title">PDO Query Validation</div>
                            <p class="contrib-desc">Verified that the SELECT * FROM members ORDER BY id query returns all rows correctly through the PDO connection defined in db.php, testing both the happy path and error handling.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128270;</span>
                            <div class="contrib-title">MySQL Installation &amp; Config</div>
                            <p class="contrib-desc">Installed MySQL on Rocky Linux 9 using dnf, ran mysql_secure_installation to remove anonymous users and test databases, and verified the service starts on boot.</p>
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
                <span class="section-badge">Implementation</span>
                <h2 class="section-title" style="font-size:1.7rem;">Database Setup Steps</h2>
                <div class="timeline mt-4">
                    <div class="tl-item">
                        <div class="tl-title">MySQL Installation</div>
                        <p class="tl-desc">Installed mysql-server via dnf, enabled and started the mysqld service with systemctl, and secured the installation using mysql_secure_installation.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">Database &amp; User Creation</div>
                        <p class="tl-desc">Created mensa_db and provisioned mensa_user@localhost with a strong password. Granted ALL PRIVILEGES on mensa_db only &mdash; not global privileges &mdash; following least-privilege principles.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">Table Schema &amp; Data Load</div>
                        <p class="tl-desc">Ran schema.sql with mysql -u root -p mensa_db &lt; schema.sql to create the members table and insert all nine team members in a single operation.</p>
                    </div>
                    <div class="tl-item" style="padding-bottom:0;">
                        <div class="tl-title">Query &amp; Output Testing</div>
                        <p class="tl-desc">Verified data with SELECT * FROM members ORDER BY id; in the MySQL shell, then confirmed the PHP page renders all members correctly in the browser.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <span class="section-badge">Skills &amp; Tools</span>
                <h2 class="section-title" style="font-size:1.7rem;">Technical Stack</h2>
                <div class="mt-4">
                    <span class="skill-tag">MySQL 8</span>
                    <span class="skill-tag">SQL DDL / DML</span>
                    <span class="skill-tag">PHP PDO</span>
                    <span class="skill-tag">Prepared Statements</span>
                    <span class="skill-tag">Database Security</span>
                    <span class="skill-tag">mysql_secure_installation</span>
                    <span class="skill-tag">Least-Privilege Grants</span>
                    <span class="skill-tag">Rocky Linux 9</span>
                    <span class="skill-tag">systemctl</span>
                    <span class="skill-tag">dnf Package Manager</span>
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
