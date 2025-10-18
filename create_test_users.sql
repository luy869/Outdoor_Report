-- 既存のユーザーアカウントのメールアドレスを変更
-- パスワードは全て "password" に統一
-- パスワードハッシュ: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- まず現在のユーザーを確認
SELECT '=== 変更前のユーザー一覧 ===' as info;
SELECT id, username, email FROM users ORDER BY id;

-- 既存の4つのユーザーのメールアドレスとパスワードを更新
UPDATE users SET email = 'test1@example.com', username = 'test1', password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE id = 1;
UPDATE users SET email = 'test2@example.com', username = 'test2', password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE id = 2;
UPDATE users SET email = 'test3@example.com', username = 'test3', password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE id = 3;
UPDATE users SET email = 'test4@example.com', username = 'test4', password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE id = 4;

-- 結果確認
SELECT '=== 変更後のユーザー一覧 ===' as info;
SELECT id, username, email FROM users ORDER BY id;
