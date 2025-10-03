-- ユーザーテーブルの作成
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `group` INT(11) NOT NULL DEFAULT 1,
    `profile_fields` TEXT,
    `last_login` INT(11) DEFAULT NULL,
    `login_hash` VARCHAR(255) DEFAULT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- テスト用ユーザーを1件追加
-- パスワードは "admin" (ハッシュ化済み)
INSERT INTO `users` (`username`, `password`, `email`, `group`, `created_at`, `updated_at`) 
VALUES ('admin', '$2y$10$rKd3qX3EZJ5JK5L5qX3qXu5qX3qXu5qX3qXu5qX3qXu5qX3qXu5qX', 'admin@example.com', 100, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
