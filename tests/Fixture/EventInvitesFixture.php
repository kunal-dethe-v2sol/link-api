<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EventInvitesFixture
 *
 */
class EventInvitesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'uuid' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'event_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'type' => ['type' => 'string', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'connection_id' => ['type' => 'uuid', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'contact_email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created_by' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'event_id' => ['type' => 'index', 'columns' => ['event_id'], 'length' => []],
            'connection_id' => ['type' => 'index', 'columns' => ['connection_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['uuid'], 'length' => []],
            'event_invites_ibfk_2' => ['type' => 'foreign', 'columns' => ['connection_id'], 'references' => ['users', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'event_invites_ibfk_1' => ['type' => 'foreign', 'columns' => ['event_id'], 'references' => ['events', 'uuid'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'uuid' => '381cb763-07cf-4bce-9eb7-b45a82558aa5',
            'event_id' => 'e2ef4e0e-bf29-475b-99f3-42d23a429ad1',
            'type' => 'Lorem ipsum dolor sit amet',
            'connection_id' => '73e352c5-71a2-4168-9bae-5fa2465ec4ef',
            'contact_email' => 'Lorem ipsum dolor sit amet',
            'status' => 1,
            'created' => '2017-08-31 11:31:55',
            'created_by' => '3d759ca5-dbc6-4f66-94e7-93773d2ec520'
        ],
    ];
}
