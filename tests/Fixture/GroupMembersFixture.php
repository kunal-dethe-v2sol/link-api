<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupMembersFixture
 *
 */
class GroupMembersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'group_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'request_type' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => 'self', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'approved_datetime' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'approved_by' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'group_id' => ['type' => 'index', 'columns' => ['group_id'], 'length' => []],
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'approved_by' => ['type' => 'index', 'columns' => ['approved_by'], 'length' => []],
            'status' => ['type' => 'index', 'columns' => ['status'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'group_user_id' => ['type' => 'unique', 'columns' => ['group_id', 'user_id'], 'length' => []],
            'group_members_ibfk_1' => ['type' => 'foreign', 'columns' => ['group_id'], 'references' => ['groups', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'group_members_ibfk_2' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'group_members_ibfk_3' => ['type' => 'foreign', 'columns' => ['approved_by'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '2ec55329-7cd8-4293-b751-e3bfe2960638',
            'group_id' => '51e411d3-c34a-4dae-a7f6-cd8383377918',
            'user_id' => 'd3350c6b-2077-49ca-a694-8d980fa51459',
            'request_type' => 'Lorem ipsum dolor sit amet',
            'approved_datetime' => '2017-08-17 10:25:38',
            'approved_by' => '454959ba-5f32-4b27-a0ff-509fd4c3c348',
            'status' => 'Lorem ipsum dolor sit amet',
            'created' => '2017-08-17 10:25:38',
            'created_by' => '12169e5f-4e0d-4d11-b8d6-f47f1d2badc3',
            'modified' => 1502965538,
            'modified_by' => 'e9e5e17b-abfa-456d-9ffc-b134ac05ce15'
        ],
    ];
}
