<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserConnectionsFixture
 *
 */
class UserConnectionsFixture extends TestFixture
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
        'connection_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => 'requested', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'connection_id' => ['type' => 'index', 'columns' => ['connection_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'user_connections_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'user_connections_ibfk_2' => ['type' => 'foreign', 'columns' => ['connection_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '30909eea-8bfb-46ba-b759-494d6d3ed914',
            'user_id' => 'de93a77a-c65f-4733-b8fb-5c7cd0604943',
            'connection_id' => '877c20c4-539a-4cd7-9e10-85d61ef2a154',
            'status' => 'Lorem ipsum dolor sit amet',
            'created' => '2017-08-23 10:05:21',
            'created_by' => 1,
            'modified' => 1503482721,
            'modified_by' => '1c7335f8-0972-4b8c-8752-044da72861a7'
        ],
    ];
}
