# MensaHost

LAMP stack web portal for the WSA Systems Administration course project (AY 2025–26).
A group of nine students provisioned and hardened a VPS, then deployed a PHP member
portal accessible via individual subdomains.

## Project Members

| Name | Reg. No. | Role |
|------|----------|------|
| Wampamba Festo | 23/U/18503/EVE | Team Leader & DevOps Engineer |
| Kawere Edrine | 23/U/09440/PS | Backup & Recovery Administrator |
| Kyarikunda Bakeine Grace | 23/U/10491/PS | UI/UX Designer |
| Kamariza Hellena | 23/U/08844/PS | UI/UX Designer |
| Talemwa Daniella | 23/U/17830/EVE | Security Management |
| Awori Betsy Hope | 23/U/07084/PS | Documentation Lead |
| Tumusiime Elvin Luke | 23/U/18113/PS | Database Management |
| Mungujakisa Maxwell | 23/U/12023/EVE | Database Management |
| Kirabo Queen Esther | 23/U/24679/PS | System Monitoring |

## Stack

- **OS:** Ubuntu Server
- **Web server:** Apache2 with virtual hosts per subdomain
- **Database:** MySQL
- **Mail:** Postfix + custom PHP SMTP mailer (no external libraries)
- **TLS:** Let's Encrypt / Certbot (wildcard cert)
- **Firewall:** UFW
- **Intrusion prevention:** Fail2Ban
- **Email auth:** DKIM + SPF

## Local Setup

```bash
# 1. Clone
git clone git@github.com:Festo-Wampamba/mensahost.git
cd mensahost

# 2. Configure DB credentials
cp mensa/db.php.example mensa/db.php
# Edit mensa/db.php — set your host, dbname, user, and password

# 3. Provision the database
mysql -u root -p < mensa/schema.sql
# When prompted, update the placeholder password in schema.sql first
```

## Deployment Evidence

`mensa/screenshots/` and `screenshots/` contain phase-by-phase evidence:
- VPS provisioning and disk specs
- Apache vhost and SSL configuration
- UFW rules and Fail2Ban setup
- DKIM/SPF DNS records
- SFTP file upload and web server verification
- Successful email delivery logs
