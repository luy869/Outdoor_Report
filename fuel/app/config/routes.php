<?php
return array(
	'_root_'  => 'report/index',  // トップページはタイムライン（誰でも閲覧可能）
	'_404_'   => 'welcome/404',    // The main 404 route

	'hello(/:name)?' => array('hello/index', 'name' => 'hello'),
);
