<?php
return array(
	'_root_'  => 'welcome/index',  // トップページ（ログインチェック後に適切にリダイレクト）
	'_404_'   => 'welcome/404',    // The main 404 route

	'hello(/:name)?' => array('hello/index', 'name' => 'hello'),
);
