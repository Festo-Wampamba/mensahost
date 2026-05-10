<?php
require_once '../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Kawere Edrine']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Kawere profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kawere Edrine &mdash; MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary:#0369a1; --primary-dark:#075985;
            --secondary:#10B981; --dark:#0f172a;
            --text:#1e293b; --muted:#64748b;
            --light-bg:#f0f9ff; --border:#bae6fd;
            --radius:12px; --shadow:0 4px 20px rgba(0,0,0,0.08);
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',system-ui,sans-serif;color:var(--text);background:#fff;}
        .navbar{background:rgba(255,255,255,0.97);border-bottom:1px solid var(--border);padding:14px 0;position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.3rem;font-weight:800;color:#4F46E5!important;}
        .navbar-brand span{color:var(--secondary);}
        .back-link{font-size:0.88rem;font-weight:500;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;}
        .back-link:hover{color:var(--primary);}
        .hero{background:linear-gradient(135deg,#0c4a6e 0%,#075985 50%,#0369a1 100%);padding:80px 0 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:450px;height:450px;background:radial-gradient(circle,rgba(16,185,129,0.15) 0%,transparent 70%);top:-60px;right:-60px;border-radius:50%;}
        .hero-avatar{width:110px;height:110px;background:linear-gradient(135deg,#0369a1,#10B981);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;margin:0 auto 24px;border:4px solid rgba(255,255,255,0.15);}
        .hero-badge{display:inline-block;background:rgba(16,185,129,0.15);color:#67e8f9;font-size:0.78rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
        .hero-name{font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;}
        .hero-role{font-size:1.1rem;color:#67e8f9;font-weight:600;margin-bottom:14px;}
        .hero-reg{font-size:0.88rem;color:rgba(255,255,255,0.5);margin-bottom:28px;}
        .hero-sub-link{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);color:#fff;font-size:0.9rem;font-weight:500;padding:10px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);transition:background 0.2s;}
        .hero-sub-link:hover{background:rgba(255,255,255,0.2);color:#fff;}
        section{padding:72px 0;}
        .section-badge{display:inline-block;background:rgba(3,105,161,0.1);color:var(--primary);font-size:0.75rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:12px;}
        .section-title{font-size:1.9rem;font-weight:700;color:var(--dark);letter-spacing:-0.4px;margin-bottom:14px;}
        .section-lead{font-size:1rem;color:var(--muted);line-height:1.75;}
        .contrib-card{background:var(--light-bg);border:1px solid var(--border);border-radius:var(--radius);padding:28px 24px;height:100%;transition:border-color 0.2s,box-shadow 0.2s;}
        .contrib-card:hover{border-color:var(--primary);box-shadow:var(--shadow);}
        .contrib-icon{font-size:2rem;margin-bottom:14px;display:block;}
        .contrib-title{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:8px;}
        .contrib-desc{font-size:0.87rem;color:var(--muted);line-height:1.65;}
        .timeline{position:relative;padding-left:28px;}
        .timeline::before{content:'';position:absolute;left:8px;top:0;bottom:0;width:2px;background:#bae6fd;}
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
        <div class="hero-avatar">K</div>
        <div class="hero-badge">&#128190; Backup &amp; Recovery</div>
        <h1 class="hero-name">Kawere Edrine</h1>
        <p class="hero-role">Backup &amp; Recovery Administrator</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="https://edrine.mensahost.tech" class="hero-sub-link">&#127760; edrine.mensahost.tech</a>
    </div>
</section>

<section>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge">About</span>
                <h2 class="section-title">Keeping Data Safe &amp; Recoverable</h2>
                <p class="section-lead">
                    Edrine manages the backup and recovery infrastructure for MensaHost. His role ensures that
                    in the event of server failure, accidental deletion, or data corruption, all web files and
                    database records can be fully restored with minimal downtime. A reliable server is only as
                    good as its last successful backup &mdash; and Edrine makes sure that backup is always ready.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#9200;</span>
                            <div class="contrib-title">Automated Cron Backups</div>
                            <p class="contrib-desc">Configured cron jobs to run nightly backups of all web directories under /var/www/ using rsync, storing date-stamped compressed archives for each run.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128451;</span>
                            <div class="contrib-title">MySQL Database Backups</div>
                            <p class="contrib-desc">Scripted automated mysqldump exports of mensa_db with the --single-transaction flag for consistent, non-blocking backups during active server use.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128260;</span>
                            <div class="contrib-title">Recovery Testing</div>
                            <p class="contrib-desc">Simulated file deletion and data loss scenarios, then verified full restoration from backup archives to confirm every member site could be recovered.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128203;</span>
                            <div class="contrib-title">Backup Policy Documentation</div>
                            <p class="contrib-desc">Documented the backup schedule, 7-day rolling retention policy, storage paths, and step-by-step restore procedures for the project report.</p>
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
                <span class="section-badge">Workflow</span>
                <h2 class="section-title" style="font-size:1.7rem;">Backup Strategy</h2>
                <div class="timeline mt-4">
                    <div class="tl-item">
                        <div class="tl-title">Backup Directory Structure</div>
                        <p class="tl-desc">Created /backup/web and /backup/db directories with restricted permissions (700), organised by date for easy navigation and point-in-time restore operations.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">rsync Web File Backups</div>
                        <p class="tl-desc">Used rsync to mirror /var/www/ nightly, preserving file permissions and only transferring changed files &mdash; making each backup fast and storage-efficient.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">mysqldump Database Exports</div>
                        <p class="tl-desc">Automated mysqldump of mensa_db with --single-transaction for InnoDB consistency. Dumps compressed with gzip and stored with date stamps.</p>
                    </div>
                    <div class="tl-item" style="padding-bottom:0;">
                        <div class="tl-title">7-Day Retention Policy</div>
                        <p class="tl-desc">Used the find command with -mtime +7 to automatically remove backups older than one week, preventing storage exhaustion on the VPS.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <span class="section-badge">Skills &amp; Tools</span>
                <h2 class="section-title" style="font-size:1.7rem;">Technical Stack</h2>
                <div class="mt-4">
                    <span class="skill-tag">rsync</span>
                    <span class="skill-tag">mysqldump</span>
                    <span class="skill-tag">cron / crontab</span>
                    <span class="skill-tag">Bash Scripting</span>
                    <span class="skill-tag">tar / gzip</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">Linux File Permissions</span>
                    <span class="skill-tag">Rocky Linux 9</span>
                    <span class="skill-tag">find command</span>
                    <span class="skill-tag">Disaster Recovery Planning</span>
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
