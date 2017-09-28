<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GroupsFixture
 *
 */
class GroupsFixture extends TestFixture
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
        'name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'slug' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'image_serialize' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Path to S3 bucket in Serialized JSON string', 'precision' => null, 'fixed' => null],
        'about' => ['type' => 'string', 'length' => 2000, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'guidelines' => ['type' => 'string', 'length' => 4000, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'visibility' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'post_approval_required' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => 'no', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'pending_member_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'total_member_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status' => ['type' => 'string', 'length' => null, 'null' => true, 'default' => 'active', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified_by' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'name' => ['type' => 'index', 'columns' => ['name'], 'length' => []],
            'visibility' => ['type' => 'index', 'columns' => ['visibility'], 'length' => []],
            'pending_member_count' => ['type' => 'index', 'columns' => ['pending_member_count'], 'length' => []],
            'total_member_count' => ['type' => 'index', 'columns' => ['total_member_count'], 'length' => []],
            'status' => ['type' => 'index', 'columns' => ['status'], 'length' => []],
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'groups_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '557d5bba-46ba-493c-bb6d-16a836d65c75',
            'user_id' => '7560dd7c-25f6-4dd4-9cab-45cb26c08606',
            'name' => 'Lorem ipsum dolor sit amet',
            'slug' => 'Lorem ipsum dolor sit amet',
            'image_serialize' => 'Lorem ipsum dolor sit amet',
            'about' => 'Lorem ipsum dolor sit amet',
            'guidelines' => 'Lorem ipsum dolor sit amet',
            'visibility' => 'Lorem ipsum dolor sit amet',
            'post_approval_required' => 'Lorem ipsum dolor sit amet',
            'pending_member_count' => 1,
            'total_member_count' => 1,
            'status' => 'Lorem ipsum dolor sit amet',
            'created' => '2017-08-17 09:48:18',
            'created_by' => 'a0541321-7afc-4e7a-a612-85ded664b97d',
            'modified' => 1502963298,
            'modified_by' => '59f1919d-d156-40e3-9bb2-6d5c35a293fd'
        ],
    ];
}
