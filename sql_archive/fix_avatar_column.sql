-- アバターカラム名の修正
-- avatar → avatar_url に変更して一貫性を持たせる

ALTER TABLE users CHANGE COLUMN avatar avatar_url VARCHAR(255) DEFAULT NULL;
