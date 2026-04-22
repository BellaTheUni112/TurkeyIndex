# A nice little PHP torrent index.

Has some nice features like rate limiting, account age requirements, an admin panel (serverip:whateverportyouused/admin.php)


# Installation

For debian-based Linux distros


`git clone https://github.com/BellaTheUni112/TurkeyIndex.git`
`cd TurkeyIndex`

CHANGE THE PASSWORD IN DB.PHP, DO IT. CHANGE IT FROM "$pdo = new PDO(", "torrent", "strongpasswordyoushouldchange");" to ", "torrent", "whatever password you want just not this exactly in quotes;"

`sudo apt update`

`sudo apt install php php-mysql mariadb-server`

`sudo systemctl enable mariadb`

`sudo systemctl start mariadb`

`sudo mysql`

In MySQL:

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

CREATE USER 'torrent'@'localhost' IDENTIFIED BY 'whatever password you used in db.php';
GRANT ALL PRIVILEGES ON torrents.* TO 'torrent'@'localhost';
FLUSH PRIVILEGES;
EXIT;

Then back in the Linux shell

`php -S 0.0.0.0:8046`

or if you want it on startup

`sudo cp -r /path/to/your/installation/ /var/www/html/torrentindex`

`sudo chown -R www-data:www-data /var/www/html/torrentindex`

`sudo chmod -R 755 /var/www/html/torrentindex`

`sudo apt install apache2 libapache2-mod-php`

`sudo systemctl enable apache2`

`sudo systemctl start apache2`

After installation, create an account then

`sudo mysql`

UPDATE users SET is_admin = 1 WHERE username = 'yourname';
