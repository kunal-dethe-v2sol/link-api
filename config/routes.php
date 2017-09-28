<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::prefix('api', function ($routes) {
    $uuid_regex = '\w{8}-\w{4}-\w{4}-\w{4}-\w{12}';

    // as found in https://book.cakephp.org/3.0/en/development/routing.html#routing-file-extensions
    $routes->extensions(['json']);
    $routes->connect('/', ['controller' => 'Cake', 'action' => 'index', 'prefix' => 'api']);

    // Restful routes
    $routes->resources('Articles');
    $routes->resources('Threads');
    $routes->resources('Posts', function ($routes) {
        $routes->resources('PostComments', [
            'inflect' => 'dasherize' // Will use ``Inflector::dasherize()``
        ]);
        $routes->resources('PostLikes', [
            'inflect' => 'dasherize' // Will use ``Inflector::dasherize()``
        ]);
    });
    $routes->resources('Events');
    $routes->resources('Trainings');
    $routes->resources('Ngos');
    $routes->resources('EnterpreneurClub', [
        'inflect' => 'dasherize',
    ]);
    $routes->resources('PremiumClub', [
        'inflect' => 'dasherize',
    ]);
    $routes->resources('Groups');

    //Custom routes
    //Users
    $routes->connect('/users/', ['controller' => 'Users', 'action' => 'index', '_method' => 'GET']);
    $routes->connect('/users/:user_id', ['controller' => 'Users', 'action' => 'view', '_method' => 'GET'], ['pass' => ['user_id'], 'user_id' => $uuid_regex]);
    $routes->connect('/users/:user_id/experience-info', ['request_type' => 'experience-info', 'controller' => 'Users', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['user_id', 'request_type'], 'user_id' => $uuid_regex]);
    $routes->connect('/users/:user_id/primary-info', ['request_type' => 'primary-info', 'controller' => 'Users', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['user_id', 'request_type'], 'user_id' => $uuid_regex]);
    $routes->connect('/users/:user_id/personal-info', ['request_type' => 'personal-info', 'controller' => 'Users', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['user_id', 'request_type'], 'user_id' => $uuid_regex]);
    $routes->connect('/users/:user_id/education-info', ['request_type' => 'education-info', 'controller' => 'Users', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['user_id', 'request_type'], 'user_id' => $uuid_regex]);
    $routes->connect('/users/:user_id/other-info', ['request_type' => 'other-info', 'controller' => 'Users', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['user_id', 'request_type'], 'user_id' => $uuid_regex]);

    //UserConnections
    $routes->connect('/connections', ['controller' => 'Userconnections', 'action' => 'index', '_method' => 'GET']);

    //Master details routes
    $routes->connect('/countries', ['controller' => 'Countries', 'action' => 'index', '_method' => 'GET']);
    $routes->connect('/states/:country_id', ['controller' => 'States', 'action' => 'index', '_method' => 'GET'], ['pass' => ['country_id'], 'country_id' => $uuid_regex]);
    $routes->connect('/cities/:state_id', ['controller' => 'Cities', 'action' => 'index', '_method' => 'GET'], ['pass' => ['state_id'], 'state_id' => $uuid_regex]);
    $routes->connect('/industries', ['request_type' => 'industry', 'controller' => 'Masters', 'action' => 'index', '_method' => 'GET'], ['pass' => [ 'request_type']]);
    $routes->connect('/functional-areas', ['request_type' => 'functional-area', 'controller' => 'Masters', 'action' => 'index', '_method' => 'GET'], ['pass' => [ 'request_type']]);
    $routes->connect('/designations', [ 'request_type' => 'designation', 'controller' => 'Masters', 'action' => 'index', '_method' => 'GET'], ['pass' => [ 'request_type']]);

    //Groups
    $routes->connect('/groups/:group_id', ['controller' => 'Groups', 'action' => 'view', '_method' => 'GET'], ['pass' => ['group_id'], 'group_id' => $uuid_regex]);

    //GroupMembers
    $routes->connect('/groups/:group_id/members', ['controller' => 'GroupMembers', 'action' => 'index', '_method' => 'GET'], ['pass' => ['group_id'], 'group_id' => $uuid_regex]);
    $routes->connect('/groups/:group_id/members', ['controller' => 'GroupMembers', 'action' => 'add', '_method' => 'POST'], ['pass' => ['group_id'], 'group_id' => $uuid_regex]);
    $routes->connect('/groups/:group_id/members/:group_member_id', ['controller' => 'GroupMembers', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['group_id', 'group_member_id'], 'group_id' => $uuid_regex, 'group_member_id' => $uuid_regex]);
    $routes->connect('/groups/:group_id/members/:group_member_id', ['controller' => 'GroupMembers', 'action' => 'delete', '_method' => 'DELETE'], ['pass' => ['group_id', 'group_member_id'], 'group_id' => $uuid_regex, 'group_member_id' => $uuid_regex]);

    //GroupMemberRequests
    $routes->connect('/groups/:group_id/members/:group_member_id/status', ['controller' => 'GroupMemberRequests', 'action' => 'edit', '_method' => 'PUT'], ['pass' => ['group_id', 'group_member_id'], 'group_id' => $uuid_regex, 'group_member_id' => $uuid_regex]);
    $routes->connect('/groups/:group_id/members/:group_member_id/status', ['controller' => 'GroupMemberRequests', 'action' => 'delete', '_method' => 'DELETE'], ['pass' => ['group_id', 'group_member_id'], 'group_id' => $uuid_regex, 'group_member_id' => $uuid_regex]);

    //Private Messaging
    $routes->connect('/threads', ['controller' => 'Threads', 'action' => 'index', '_method' => 'GET', 'action' => 'delete', '_method' => 'DELETE']);
    $routes->connect('/threads/:thread_id', ['controller' => 'Threads', 'action' => 'view', '_method' => 'GET',], ['pass' => ['thread_id'], 'thread_id' => $uuid_regex]);
    $routes->connect('/threads/messages', ['controller' => 'ThreadMessages', 'action' => 'add', '_method' => 'POST']);
    $routes->connect('/threads/:thread_id/messages', ['controller' => 'ThreadMessages', 'action' => 'index', '_method' => 'GET',], ['pass' => ['thread_id'], 'thread_id' => $uuid_regex]);
    $routes->connect('/threads/:thread_id/messages/export', ['controller' => 'ThreadMessages', 'action' => 'export', '_method' => 'GET'], ['pass' => ['thread_id'], 'thread_id' => $uuid_regex]);
    $routes->connect('/threads/:thread_id/users', ['controller' => 'ThreadUsers', 'action' => 'add', '_method' => 'POST'], ['pass' => ['thread_id'], 'thread_id' => $uuid_regex]);
    
    //User registraton and management
    $routes->connect('/login', ['controller' => 'Auth', 'action' => 'login', 'prefix' => 'api']);
    $routes->connect('/signup', ['controller' => 'Users', 'action' => 'add', 'prefix' => 'api']);
    $routes->connect('/users/:user_id/set-password/:password_token', [
        'request_type' => 'set-password',
        'controller' => 'Auth',
        'action' => 'setPassword',
        'prefix' => 'api'], [
        'user_id' => $uuid_regex,
        'password_token' => '\w{64}',
        'pass' => ['user_id', 'request_type', 'password_token'],
        '_name' => 'set-password']);
    $routes->connect('/request/otp', ['controller' => 'Auth', 'action' => 'setOtp', 'prefix' => 'api']);
    $routes->connect('/request/password', ['controller' => 'Auth', 'action' => 'forgotPassword', 'prefix' => 'api']);
    $routes->connect('/request/social-details', ['controller' => 'Auth', 'action' => 'getSocialDetails', 'prefix' => 'api']);

    //Manage Posts
    $routes->connect('/sharepost/:post_id', ['controller' => 'Posts', 'action' => 'sharepost', 'prefix' => 'api']);

    //$routes->fallbacks('InflectedRoute');
});

/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
Router::extensions(['csv']);
