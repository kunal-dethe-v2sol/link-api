<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserConnectionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserConnectionsTable Test Case
 */
class UserConnectionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserConnectionsTable
     */
    public $UserConnections;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_connections',
        'app.users',
        'app.countries',
        'app.states',
        'app.cities',
        'app.roles',
        'app.action_reasons',
        'app.article_comments',
        'app.article_likes',
        'app.article_sharewith_email',
        'app.article_sharewith_groups',
        'app.article_sharewith_people',
        'app.articles',
        'app.group_members',
        'app.groups',
        'app.post_sharewith_groups',
        'app.posts',
        'app.post_comments',
        'app.post_likes',
        'app.post_sharewith_email',
        'app.post_sharewith_people',
        'app.post_social_links',
        'app.messages',
        'app.threads',
        'app.thread_users',
        'app.user_accounts',
        'app.masters',
        'app.user_exp'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserConnections') ? [] : ['className' => UserConnectionsTable::class];
        $this->UserConnections = TableRegistry::get('UserConnections', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserConnections);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
