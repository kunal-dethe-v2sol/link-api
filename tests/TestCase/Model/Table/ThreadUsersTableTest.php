<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ThreadUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ThreadUsersTable Test Case
 */
class ThreadUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ThreadUsersTable
     */
    public $ThreadUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.thread_users',
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
        'app.messages',
        'app.threads',
        'app.post_comments',
        'app.post_likes',
        'app.post_sharewith_email',
        'app.post_sharewith_groups',
        'app.post_sharewith_people',
        'app.posts',
        'app.post_social_links',
        'app.user_accounts',
        'app.masters',
        'app.user_connections',
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
        $config = TableRegistry::exists('ThreadUsers') ? [] : ['className' => ThreadUsersTable::class];
        $this->ThreadUsers = TableRegistry::get('ThreadUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ThreadUsers);

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
