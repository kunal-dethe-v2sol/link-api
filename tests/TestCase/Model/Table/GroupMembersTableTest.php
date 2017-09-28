<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GroupMembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GroupMembersTable Test Case
 */
class GroupMembersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GroupMembersTable
     */
    public $GroupMembers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.group_members',
        'app.groups',
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
        'app.messages',
        'app.threads',
        'app.thread_users',
        'app.post_comments',
        'app.posts',
        'app.post_likes',
        'app.post_sharewith_email',
        'app.post_sharewith_groups',
        'app.post_sharewith_people',
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
        $config = TableRegistry::exists('GroupMembers') ? [] : ['className' => GroupMembersTable::class];
        $this->GroupMembers = TableRegistry::get('GroupMembers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GroupMembers);

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
