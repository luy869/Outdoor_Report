<?php

class Controller_Quiz extends Controller_Template
{
	/**
	 * クイズトップページ
	 */
	public function action_index()
	{
		$user_id = Session::get('user_id');

		// 統計情報を取得
		$data = array();
		
		if ($user_id) {
			// 総回答数
			$total_attempts = DB::select(DB::expr('COUNT(*) as count'))
				->from('quiz_attempts')
				->where('user_id', $user_id)
				->execute()
				->current();
			$data['total_attempts'] = (int)$total_attempts['count'];

			// 正解数
			$correct_count = DB::select(DB::expr('COUNT(*) as count'))
				->from('quiz_attempts')
				->where('user_id', $user_id)
				->where('is_correct', 1)
				->execute()
				->current();
			$data['correct_count'] = (int)$correct_count['count'];

			// 正解率
			$data['accuracy'] = $data['total_attempts'] > 0 
				? round(($data['correct_count'] / $data['total_attempts']) * 100, 1) 
				: 0;
		} else {
			$data['total_attempts'] = 0;
			$data['correct_count'] = 0;
			$data['accuracy'] = 0;
		}

		// クイズ総数
		$total_quizzes = DB::select(DB::expr('COUNT(*) as count'))
			->from('quizzes')
			->execute()
			->current();
		$data['total_quizzes'] = (int)$total_quizzes['count'];

		$this->template->title = 'アウトドアクイズ';
		$this->template->content = View::forge('quiz/index', $data);
	}

	/**
	 * クイズプレイ
	 */
	public function action_play($difficulty = null)
	{
		// ランダムに1問取得
		$query = DB::select()
			->from('quizzes');
		
		if ($difficulty && in_array($difficulty, ['easy', 'medium', 'hard'])) {
			$query->where('difficulty', $difficulty);
		}
		
		$query->order_by(DB::expr('RAND()'))
			->limit(1);
		
		$quiz = $query->execute()->current();

		if (!$quiz) {
			Session::set_flash('error', 'クイズが見つかりません');
			Response::redirect('quiz/index');
		}

		$data = array();
		$data['quiz_id'] = (int)$quiz['id'];
		$data['question'] = (string)$quiz['question'];
		$data['option_a'] = (string)$quiz['option_a'];
		$data['option_b'] = (string)$quiz['option_b'];
		$data['option_c'] = (string)$quiz['option_c'];
		$data['option_d'] = (string)$quiz['option_d'];
		$data['difficulty'] = (string)$quiz['difficulty'];
		$data['category'] = (string)$quiz['category'];

		$this->template->title = 'クイズに挑戦';
		$this->template->content = View::forge('quiz/play', $data);
	}

	/**
	 * クイズ回答処理
	 */
	public function action_answer()
	{
		if (Input::method() !== 'POST') {
			Response::redirect('quiz/index');
		}

		$quiz_id = (int)Input::post('quiz_id');
		$user_answer = Input::post('answer');

		// クイズ情報を取得
		$quiz = DB::select()
			->from('quizzes')
			->where('id', $quiz_id)
			->execute()
			->current();

		if (!$quiz) {
			Session::set_flash('error', 'クイズが見つかりません');
			Response::redirect('quiz/index');
		}

		// 正解判定
		$is_correct = ($user_answer === $quiz['correct_answer']);

		// ログイン中なら履歴を保存
		$user_id = Session::get('user_id');
		if ($user_id) {
			DB::insert('quiz_attempts')
				->set(array(
					'user_id' => $user_id,
					'quiz_id' => $quiz_id,
					'user_answer' => $user_answer,
					'is_correct' => $is_correct ? 1 : 0,
					'answered_at' => date('Y-m-d H:i:s'),
				))
				->execute();
		}

		// 結果ページにデータを渡す
		$data = array();
		$data['is_correct'] = $is_correct;
		$data['user_answer'] = (string)$user_answer;
		$data['correct_answer'] = (string)$quiz['correct_answer'];
		$data['question'] = (string)$quiz['question'];
		$data['option_a'] = (string)$quiz['option_a'];
		$data['option_b'] = (string)$quiz['option_b'];
		$data['option_c'] = (string)$quiz['option_c'];
		$data['option_d'] = (string)$quiz['option_d'];
		$data['explanation'] = (string)$quiz['explanation'];
		$data['difficulty'] = (string)$quiz['difficulty'];

		$this->template->title = 'クイズ結果';
		$this->template->content = View::forge('quiz/result', $data);
	}

	/**
	 * ランキング
	 */
	public function action_ranking()
	{
		// ユーザー別の正解率ランキング
		$rankings = DB::select(
				'users.id',
				'users.username',
				'users.avatar_url',
				DB::expr('COUNT(*) as total'),
				DB::expr('SUM(quiz_attempts.is_correct) as correct'),
				DB::expr('ROUND((SUM(quiz_attempts.is_correct) / COUNT(*)) * 100, 1) as accuracy')
			)
			->from('quiz_attempts')
			->join('users', 'INNER')
			->on('quiz_attempts.user_id', '=', 'users.id')
			->group_by('users.id')
			->having(DB::expr('COUNT(*)'), '>=', 5) // 最低5問回答している人のみ
			->order_by('accuracy', 'DESC')
			->order_by('total', 'DESC')
			->limit(10)
			->execute();

		$data = array();
		$data['rankings'] = array();
		
		if ($rankings) {
			foreach ($rankings as $rank) {
				$data['rankings'][] = array(
					'user_id' => (int)$rank['id'],
					'username' => (string)$rank['username'],
					'avatar_url' => $rank['avatar_url'] ? (string)$rank['avatar_url'] : '',
					'total' => (int)$rank['total'],
					'correct' => (int)$rank['correct'],
					'accuracy' => (float)$rank['accuracy']
				);
			}
		}

		$this->template->title = 'クイズランキング';
		$this->template->content = View::forge('quiz/ranking', $data);
	}
}
