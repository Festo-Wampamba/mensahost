CREATE DATABASE IF NOT EXISTS mensa_db;
CREATE USER IF NOT EXISTS 'mensa_user'@'localhost' IDENTIFIED BY 'Pass2026!';
GRANT ALL PRIVILEGES ON mensa_db.* TO 'mensa_user'@'localhost';
FLUSH PRIVILEGES;

USE mensa_db;

CREATE TABLE IF NOT EXISTS members (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(100) NOT NULL,
    registration_number VARCHAR(50)  NOT NULL,
    role                VARCHAR(100) NOT NULL,
    subdomain           VARCHAR(100) NOT NULL
);

INSERT INTO members (name, registration_number, role, subdomain) VALUES
('Wampamba Festo',          '23/U/18503/EVE', 'Team Leader & DevOps Engineer',       'festo.mensahost.tech'),
('Kawere Edrine',           '23/U/09440/PS',  'Backup & Recovery Administrator',     'edrine.mensahost.tech'),
('Kyarikunda Bakeine Grace','23/U/10491/PS',  'UI/UX Designer',                      'grace.mensahost.tech'),
('Kamariza Hellena',        '23/U/08844/PS',  'UI/UX Designer',                      'hellena.mensahost.tech'),
('Talemwa Daniella',        '23/U/17830/EVE', 'Security Management',                 'daniella.mensahost.tech'),
('Awori Betsy Hope',        '23/U/07084/PS',  'Documentation Lead',                  'betsy.mensahost.tech'),
('Tumusiime Elvin Luke',    '23/U/18113/PS',  'Database Management',                 'elvin.mensahost.tech'),
('Mungujakisa Maxwell',     '23/U/12023/EVE', 'Database Management',                 'maxwell.mensahost.tech'),
('Kirabo Queen Esther',     '23/U/24679/PS',  'System Monitoring',                   'esther.mensahost.tech');
