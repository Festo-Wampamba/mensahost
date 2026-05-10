<?php
require_once '../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Kirabo Queen Esther']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Kirabo profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirabo Queen Esther &mdash; MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--primary:#7c3aed;--primary-dark:#6d28d9;--secondary:#10B981;--dark:#0f172a;--text:#1e293b;--muted:#64748b;--light-bg:#f5f3ff;--border:#ddd6fe;--radius:12px;--shadow:0 4px 20px rgba(0,0,0,0.08);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',system-ui,sans-serif;color:var(--text);background:#fff;}
        .navbar{background:rgba(255,255,255,0.97);border-bottom:1px solid var(--border);padding:14px 0;position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.3rem;font-weight:800;color:#4F46E5!important;}
        .navbar-brand span{color:var(--secondary);}
        .back-link{font-size:0.88rem;font-weight:500;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;}
        .back-link:hover{color:var(--primary);}
        .hero{background:linear-gradient(135deg,#1e0a3c 0%,#3b0764 50%,#7c3aed 100%);padding:80px 0 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:450px;height:450px;background:radial-gradient(circle,rgba(167,139,250,0.2) 0%,transparent 70%);top:-60px;right:-60px;border-radius:50%;}
        .hero-avatar{width:110px;height:110px;background:linear-gradient(135deg,#7c3aed,#a78bfa);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;margin:0 auto 24px;border:4px solid rgba(255,255,255,0.15);}
        .hero-badge{display:inline-block;background:rgba(167,139,250,0.2);color:#a78bfa;font-size:0.78rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
        .hero-name{font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;}
        .hero-role{font-size:1.1rem;color:#a78bfa;font-weight:600;margin-bottom:14px;}
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
        .timeline{position:relative;padding-left:28px;}
        .timeline::before{content:'';position:absolute;left:8px;top:0;bottom:0;width:2px;background:#ddd6fe;}
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
        <div class="hero-badge">&#128202; System Monitoring</div>
        <h1 class="hero-name">Kirabo Queen Esther</h1>
        <p class="hero-role">System Monitoring Officer</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="https://esther.mensahost.tech" class="hero-sub-link">&#127760; esther.mensahost.tech</a>
    </div>
</section>

<section>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge">About</span>
                <h2 class="section-title">Watching Over the MensaHost Server</h2>
                <p class="section-lead">
                    Esther is responsible for monitoring the health, performance, and availability of the
                    MensaHost server. By tracking system resource usage, reading Apache and system logs,
                    and verifying that all services are running correctly, she ensures the team is always
                    aware of the server's state and can respond quickly to any issues.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128200;</span>
                            <div class="contrib-title">Resource Usage Monitoring</div>
                            <p class="contrib-desc">Monitored CPU, memory, and disk usage using top, htop, df -h, and free -m. Documented baseline resource consumption at idle and under simulated load.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128220;</span>
                            <div class="contrib-title">Log Analysis</div>
                            <p class="contrib-desc">Reviewed Apache access and error logs (/var/log/httpd/) and system logs via journalctl to identify errors, unusual access patterns, and verify that all virtual hosts are serving requests correctly.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128337;</span>
                            <div class="contrib-title">Service Uptime Checks</div>
                            <p class="contrib-desc">Used systemctl status to verify that httpd, mysqld, fail2ban, and sshd are active and running. Checked that all services are enabled to restart automatically on reboot.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128737;</span>
                            <div class="contrib-title">Fail2Ban Activity Monitoring</div>
                            <p class="contrib-desc">Monitored Fail2Ban ban activity via fail2ban-client status sshd, tracked banned IP counts, and verified that legitimate team members were not accidentally banned during testing.</p>
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
                <span class="section-badge">Monitoring Workflow</span>
                <h2 class="section-title" style="font-size:1.7rem;">Key Monitoring Activities</h2>
                <div class="timeline mt-4">
                    <div class="tl-item">
                        <div class="tl-title">System Resource Baseline</div>
                        <p class="tl-desc">Recorded CPU, RAM, and disk usage at project start to establish a performance baseline. Used this to detect anomalies during the submission period.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">Apache Virtual Host Verification</div>
                        <p class="tl-desc">Tested every subdomain in a browser and via curl to confirm all nine member sites and the main domain loaded correctly over both HTTP and HTTPS.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">SSL Certificate Expiry Checks</div>
                        <p class="tl-desc">Used openssl s_client and certbot certificates to verify all SSL certificates are valid, not expired, and that auto-renewal is correctly configured via systemd.</p>
                    </div>
                    <div class="tl-item" style="padding-bottom:0;">
                        <div class="tl-title">Server Health Report</div>
                        <p class="tl-desc">Compiled a server health summary for inclusion in the project report, including uptime (uptime command), disk usage, active services, and firewall rule count.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <span class="section-badge">Skills &amp; Tools</span>
                <h2 class="section-title" style="font-size:1.7rem;">Technical Stack</h2>
                <div class="mt-4">
                    <span class="skill-tag">top / htop</span>
                    <span class="skill-tag">journalctl</span>
                    <span class="skill-tag">Apache Logs</span>
                    <span class="skill-tag">systemctl</span>
                    <span class="skill-tag">df / du</span>
                    <span class="skill-tag">free -m</span>
                    <span class="skill-tag">curl</span>
                    <span class="skill-tag">openssl s_client</span>
                    <span class="skill-tag">fail2ban-client</span>
                    <span class="skill-tag">uptime</span>
                    <span class="skill-tag">Rocky Linux 9</span>
                    <span class="skill-tag">Server Health Reporting</span>
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
