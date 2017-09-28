<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ThreadUsersFixture
 *
 */
class ThreadUsersFixture extends TestFixture
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
        'thread_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'start_message_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'last_message_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => 'active', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'thread_id' => ['type' => 'index', 'columns' => ['thread_id'], 'length' => []],
            'last_message_id' => ['type' => 'index', 'columns' => ['last_message_id'], 'length' => []],
            'start_message_id' => ['type' => 'index', 'columns' => ['start_message_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'thread_users_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'thread_users_ibfk_2' => ['type' => 'foreign', 'columns' => ['thread_id'], 'references' => ['threads', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'thread_users_ibfk_3' => ['type' => 'foreign', 'columns' => ['start_message_id'], 'references' => ['messages', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'thread_users_ibfk_4' => ['type' => 'foreign', 'columns' => ['last_message_id'], 'references' => ['messages', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '50049aa6-939d-46dc-bc5b-fcc86ca634f5',
            'user_id' => '1473bff6-3aac-42b9-8613-49f2943bd026',
            'thread_id' => 'd57ad4ff-3910-462e-905c-98d266057377',
            'start_message_id' => '7797a489-a4b3-4e67-b85e-92a9775c6613',
            'last_message_id' => '79d58761-3a0e-44b2-b4d3-86b7dd9b4a54',
            'status' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
