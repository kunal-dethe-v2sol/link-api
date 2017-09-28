<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PostLikesFixture
 *
 */
class PostLikesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'post_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => 'active', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'post_id' => ['type' => 'index', 'columns' => ['post_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'post_likes_ibfk_2' => ['type' => 'foreign', 'columns' => ['post_id'], 'references' => ['posts', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'post_likes_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'uuid' => '37ffdc16-e2b8-4cb5-8e8a-54c956c7acab',
            'user_id' => '16c10b39-70fe-47da-aee3-7a4b2b3b5337',
            'post_id' => '58346e47-0d04-4a7f-8705-9d91da851a8d',
            'status' => 'Lorem ipsum dolor sit amet',
            'created' => '2017-08-21 12:33:53',
            'created_by' => 'e71c498c-be2e-4ea8-9fe0-935ab5bfb1f8',
            'modified' => 1503318833,
            'modified_by' => 'f062668d-4c91-4315-8dd3-b8f181928f9a'
        ],
    ];
}
