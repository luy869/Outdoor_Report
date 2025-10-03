<?php

class Model_User extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'username',
        'email',
        'password',
        'created_at',
    );

    protected static $_table_name = 'users';

    // レポートとのリレーション
    protected static $_has_many = array(
        'reports' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Report',
            'key_to' => 'user_id',
        )
    );
}
