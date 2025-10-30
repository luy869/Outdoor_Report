-- クイズテーブル
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_answer CHAR(1) NOT NULL, -- 'a', 'b', 'c', 'd'
    explanation TEXT,
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_difficulty (difficulty),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ユーザーのクイズ回答履歴
CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    user_answer CHAR(1) NOT NULL,
    is_correct BOOLEAN NOT NULL,
    answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_quiz_id (quiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- サンプルクイズデータ
INSERT INTO quizzes (question, option_a, option_b, option_c, option_d, correct_answer, explanation, difficulty, category) VALUES
('日本で最も高い山は？', '富士山', '北岳', '奥穂高岳', '槍ヶ岳', 'a', '富士山は標高3,776mで日本一高い山です。', 'easy', '登山'),
('登山用語で「ガレ場」とは何を指す？', '雪渓', '岩が積み重なった急斜面', '急な登り', '平坦な道', 'b', 'ガレ場は岩や石が積み重なった不安定な斜面のことです。', 'medium', '登山'),
('日本百名山の選定者は誰？', '深田久弥', '田部重治', '加藤文太郎', '槇有恒', 'a', '深田久弥が1964年に『日本百名山』を発表しました。', 'medium', '登山'),
('テント泊登山で重要な「ペグ」とは？', 'テントを固定する杭', '調理器具', '水筒', 'ザックカバー', 'a', 'ペグはテントやタープを地面に固定するための杭です。', 'easy', 'キャンプ'),
('標高が100m上がるごとに気温は約何度下がる？', '0.3度', '0.6度', '1度', '1.5度', 'b', '標高が100m上がるごとに気温は約0.6度下がります。', 'hard', '気象'),
('日本アルプスに含まれないのはどれ？', '北アルプス', '中央アルプス', '南アルプス', '東アルプス', 'd', '日本アルプスは北・中央・南の3つのアルプスから構成されます。', 'easy', '地理'),
('登山での「ビバーク」とは何を意味する？', '休憩', '緊急露営', '下山', '頂上到達', 'b', 'ビバークは予定外の緊急露営のことを指します。', 'medium', '登山'),
('富士山の登山シーズンはいつ？', '3月〜5月', '6月〜8月', '7月〜9月', '10月〜12月', 'c', '富士山の登山シーズンは7月上旬から9月上旬までです。', 'easy', '登山');
