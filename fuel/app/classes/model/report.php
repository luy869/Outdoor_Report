<?php

class Model_Report extends \Orm\Model
{
    protected static $_properties = array(
        'id',
        'user_id',
        'location_id',
        'title',
        'body',
        'visit_date',
        'privacy',
        'created_at',
        'updated_at',
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => true,
        ),
    );

    protected static $_table_name = 'reports';

    // ユーザーとのリレーション
    protected static $_belongs_to = array(
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
        )
    );

    // バリデーションルール
    public static function validate($factory)
    {
        $val = Validation::forge($factory);
        $val->add_field('title', 'タイトル', 'required|max_length[32]');
        $val->add_field('body', '本文', 'required');
        $val->add_field('visit_date', '訪問日', 'required|valid_date');
        return $val;
    }
}
