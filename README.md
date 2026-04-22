# TurkeyIndex

A simple PHP-based torrent index with:
- User accounts (login/register)
- Account age restrictions
- Upload rate limiting
- Admin panel (`/admin.php`)
- MySQL/MariaDB backend
- Pagination + search

---

# Installation (Debian-based Linux)

## 1. Clone the project

```bash
git clone https://github.com/BellaTheUni112/TurkeyIndex.git
cd TurkeyIndex
```

---

## 2. Configure database credentials

IMPORTANT: Change your DB password in `db.php`

```php
$pdo = new PDO(
  "mysql:host=localhost;dbname=torrents;charset=utf8mb4",
  "torrent",
  "your_secure_password_here"
);
```

---

## 3. Install dependencies

```bash
sudo apt update
sudo apt install php php-mysql mariadb-server
```

---

## 4. Start MariaDB

```bash
sudo systemctl enable mariadb
sudo systemctl start mariadb
```

---

## 5. Create database + tables

```bash
sudo mysql
```

```sql
CREATE DATABASE torrents;
USE torrents;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE torrents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  info_hash VARCHAR(40) UNIQUE,
  magnet TEXT,
  size BIGINT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE uploads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 6. Create database user

```sql
CREATE USER 'torrent'@'localhost' IDENTIFIED BY 'your_secure_password_here';
GRANT ALL PRIVILEGES ON torrents.* TO 'torrent'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 7. Run development server

```bash
php -S 0.0.0.0:8046
```

Open:
```
http://localhost:8046
```

---

## 8. Production setup (Apache)

```bash
sudo apt install apache2 libapache2-mod-php

sudo cp -r . /var/www/html/torrentindex

sudo chown -R www-data:www-data /var/www/html/torrentindex
sudo chmod -R 755 /var/www/html/torrentindex

sudo systemctl enable apache2
sudo systemctl start apache2
```

Open:
```
http://localhost/torrentindex
```

---

## 9. Make yourself admin

```bash
sudo mysql
```

```sql
USE torrents;

UPDATE users
SET is_admin = 1
WHERE username = 'yourname';
```

Then log out and log back in.

---

# Notes

- Admin panel: `/admin.php`
- Users must:
  - Be logged in
  - Be 14+ days old to upload
  - Respect upload rate limits

---

# Security warning

This is a learning project. Do not expose publicly without adding CSRF protection, stricter validation, and hardening.
