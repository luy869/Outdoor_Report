-- bioカラムの追加
-- ユーザープロフィールに自己紹介文を追加

ALTER TABLE users ADD COLUMN IF NOT EXISTS bio TEXT DEFAULT NULL AFTER avatar_url;
