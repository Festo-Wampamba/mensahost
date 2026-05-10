<?php
require_once '../../db.php';

$member   = null;
$db_error = false;
try {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE name = ?");
    $stmt->execute(['Talemwa Daniella']);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $db_error = true;
    error_log("Talemwa profile query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talemwa Daniella &mdash; MensaHost</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{--primary:#dc2626;--primary-dark:#b91c1c;--secondary:#10B981;--dark:#0f172a;--text:#1e293b;--muted:#64748b;--light-bg:#fff5f5;--border:#fecaca;--radius:12px;--shadow:0 4px 20px rgba(0,0,0,0.08);}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Poppins',system-ui,sans-serif;color:var(--text);background:#fff;}
        .navbar{background:rgba(255,255,255,0.97);border-bottom:1px solid var(--border);padding:14px 0;position:sticky;top:0;z-index:100;}
        .navbar-brand{font-size:1.3rem;font-weight:800;color:#4F46E5!important;}
        .navbar-brand span{color:var(--secondary);}
        .back-link{font-size:0.88rem;font-weight:500;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:6px;}
        .back-link:hover{color:var(--primary);}
        .hero{background:linear-gradient(135deg,#450a0a 0%,#7f1d1d 50%,#dc2626 100%);padding:80px 0 60px;position:relative;overflow:hidden;}
        .hero::before{content:'';position:absolute;width:450px;height:450px;background:radial-gradient(circle,rgba(252,165,165,0.15) 0%,transparent 70%);top:-60px;right:-60px;border-radius:50%;}
        .hero-avatar{width:110px;height:110px;background:linear-gradient(135deg,#dc2626,#f87171);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;margin:0 auto 24px;border:4px solid rgba(255,255,255,0.15);}
        .hero-badge{display:inline-block;background:rgba(252,165,165,0.2);color:#fca5a5;font-size:0.78rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:16px;}
        .hero-name{font-size:clamp(2rem,4vw,3rem);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:8px;}
        .hero-role{font-size:1.1rem;color:#fca5a5;font-weight:600;margin-bottom:14px;}
        .hero-reg{font-size:0.88rem;color:rgba(255,255,255,0.5);margin-bottom:28px;}
        .hero-sub-link{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);color:#fff;font-size:0.9rem;font-weight:500;padding:10px 22px;border-radius:10px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);transition:background 0.2s;}
        .hero-sub-link:hover{background:rgba(255,255,255,0.2);color:#fff;}
        section{padding:72px 0;}
        .section-badge{display:inline-block;background:rgba(220,38,38,0.1);color:var(--primary);font-size:0.75rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;padding:5px 14px;border-radius:20px;margin-bottom:12px;}
        .section-title{font-size:1.9rem;font-weight:700;color:var(--dark);letter-spacing:-0.4px;margin-bottom:14px;}
        .section-lead{font-size:1rem;color:var(--muted);line-height:1.75;}
        .contrib-card{background:var(--light-bg);border:1px solid var(--border);border-radius:var(--radius);padding:28px 24px;height:100%;transition:border-color 0.2s,box-shadow 0.2s;}
        .contrib-card:hover{border-color:var(--primary);box-shadow:var(--shadow);}
        .contrib-icon{font-size:2rem;margin-bottom:14px;display:block;}
        .contrib-title{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:8px;}
        .contrib-desc{font-size:0.87rem;color:var(--muted);line-height:1.65;}
        .timeline{position:relative;padding-left:28px;}
        .timeline::before{content:'';position:absolute;left:8px;top:0;bottom:0;width:2px;background:#fecaca;}
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
        <div class="hero-avatar">D</div>
        <div class="hero-badge">&#128737; Security Management</div>
        <h1 class="hero-name">Talemwa Daniella</h1>
        <p class="hero-role">Security Management Officer</p>
        <?php if ($member): ?>
            <p class="hero-reg">Reg No: <?= htmlspecialchars($member['registration_number'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <a href="https://daniella.mensahost.tech" class="hero-sub-link">&#127760; daniella.mensahost.tech</a>
    </div>
</section>

<section>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge">About</span>
                <h2 class="section-title">Securing the MensaHost Infrastructure</h2>
                <p class="section-lead">
                    Daniella is responsible for the security posture of the entire MensaHost server environment.
                    From firewall configuration and intrusion prevention to SSL certificate management and SSH
                    hardening, she ensures the VPS is protected against the most common attack vectors faced
                    by public-facing web servers.
                </p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128293;</span>
                            <div class="contrib-title">UFW Firewall Configuration</div>
                            <p class="contrib-desc">Configured UFW with a deny-all default incoming policy, then opened only required ports: 22 (SSH), 80 (HTTP), 443 (HTTPS), and the FTP passive range. Documented every rule.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128274;</span>
                            <div class="contrib-title">Fail2Ban Intrusion Prevention</div>
                            <p class="contrib-desc">Installed and configured Fail2Ban to monitor SSH and Apache logs, banning IPs after 5 failed attempts within 10 minutes. Tested ban/unban functionality and documented results.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128272;</span>
                            <div class="contrib-title">SSL/TLS Certificates</div>
                            <p class="contrib-desc">Installed Certbot and obtained Let's Encrypt certificates for mensahost.tech, www.mensahost.tech, and all nine member subdomains. Configured HTTP-to-HTTPS redirection in Apache.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="contrib-card">
                            <span class="contrib-icon">&#128421;</span>
                            <div class="contrib-title">SSH Hardening</div>
                            <p class="contrib-desc">Verified SSH key-based authentication, confirmed PasswordAuthentication and PermitRootLogin are disabled in sshd_config, and changed the default SSH port to reduce automated scanning noise.</p>
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
                <span class="section-badge">Security Measures</span>
                <h2 class="section-title" style="font-size:1.7rem;">Hardening Checklist</h2>
                <div class="timeline mt-4">
                    <div class="tl-item">
                        <div class="tl-title">Default-Deny Firewall Policy</div>
                        <p class="tl-desc">Set UFW default to deny all incoming traffic and allow all outgoing. Only whitelisted ports are open, minimising the attack surface of the VPS.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">Fail2Ban SSH &amp; Apache Jails</div>
                        <p class="tl-desc">Enabled the sshd and apache-auth jails in Fail2Ban. Configured maxretry=5, findtime=600, bantime=3600. Verified banning works by monitoring /var/log/fail2ban.log.</p>
                    </div>
                    <div class="tl-item">
                        <div class="tl-title">Let's Encrypt Auto-Renewal</div>
                        <p class="tl-desc">Verified that Certbot installs a systemd timer for automatic certificate renewal before the 90-day expiry. Tested renewal with certbot renew --dry-run.</p>
                    </div>
                    <div class="tl-item" style="padding-bottom:0;">
                        <div class="tl-title">Port Audit &amp; Documentation</div>
                        <p class="tl-desc">Ran ss -tlnp to list all listening services. Documented every open port, the service it serves, and the justification for keeping it open in the project report.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <span class="section-badge">Skills &amp; Tools</span>
                <h2 class="section-title" style="font-size:1.7rem;">Technical Stack</h2>
                <div class="mt-4">
                    <span class="skill-tag">UFW Firewall</span>
                    <span class="skill-tag">Fail2Ban</span>
                    <span class="skill-tag">Certbot</span>
                    <span class="skill-tag">Let's Encrypt</span>
                    <span class="skill-tag">OpenSSH Hardening</span>
                    <span class="skill-tag">SSL/TLS</span>
                    <span class="skill-tag">Apache Security</span>
                    <span class="skill-tag">Linux Logs</span>
                    <span class="skill-tag">Network Security</span>
                    <span class="skill-tag">Rocky Linux 9</span>
                    <span class="skill-tag">systemd</span>
                    <span class="skill-tag">ss / netstat</span>
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
