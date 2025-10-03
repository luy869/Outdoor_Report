<?php
return array(
	'_root_'  => 'report/index',  // トップページをレポート一覧に変更
	'_404_'   => 'welcome/404',    // The main 404 route

	'hello(/:name)?' => array('hello/index', 'name' => 'hello'),
);
