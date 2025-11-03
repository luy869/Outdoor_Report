-- photosテーブルに並び順カラムを追加（将来的な拡張用）
-- 現在は未使用だが、ドラッグ&ドロップでの並び替え機能実装時に使用可能
ALTER TABLE photos 
ADD COLUMN sort_order INT NOT NULL DEFAULT 0 AFTER image_url,
ADD INDEX idx_report_sort (report_id, sort_order);

-- 既存データに並び順を設定
UPDATE photos SET sort_order = id WHERE sort_order = 0;
