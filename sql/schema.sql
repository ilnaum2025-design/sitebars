CREATE DATABASE IF NOT EXISTS sitebars CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sitebars;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS razdels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS price (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  is_from TINYINT(1) NOT NULL DEFAULT 1,
  cost VARCHAR(50) NOT NULL,
  razdel_id INT NOT NULL,
  CONSTRAINT fk_price_razdel FOREIGN KEY (razdel_id) REFERENCES razdels(id) ON DELETE CASCADE
);

INSERT INTO admins (login, password) VALUES ('admin', 'admin123')
ON DUPLICATE KEY UPDATE login = VALUES(login);

INSERT INTO razdels (title) VALUES ('Стрижки'), ('Борода')
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO price (title, is_from, cost, razdel_id)
SELECT 'Мужская стрижка', 1, '1500', r.id FROM razdels r WHERE r.title='Стрижки'
UNION ALL
SELECT 'Оформление бороды', 0, '900', r.id FROM razdels r WHERE r.title='Борода';
