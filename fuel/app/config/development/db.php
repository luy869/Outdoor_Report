<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'hostname'   => 'db',
			'database'   => 'outdoor',
			'username'   => 'root',
			'password'   => '3556',
			'persistent' => false,
		),
		'identifier'   => '`',
		'table_prefix' => '',
		'charset'      => 'utf8',
		'collation'    => 'utf8_unicode_ci',
		'enable_cache' => true,
		'profiling'    => false,
	),
);
