<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;

/**
 * UserConnections Controller
 *
 * @property \App\Model\Table\UserConnectionsTable $UserConnections
 *
 * @method \App\Model\Entity\UserConnection[] paginate($object = null, array $settings = [])
 */
class UserConnectionsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('UserConnections');
    }
    
    /**
     * Index method
     * 
     * 1. User Connection List
     * 2. Search User Connections
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $response = array(
            'code' => 500,
            'data' => array(),
        );

        if ($this->request->is('get')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();
                
                $search = $this->request->getQuery('search', '');
                $page = $this->request->getQuery('page', 1);
                $limit = $this->request->getQuery('limit', Configure::read('SITE_PAGINATION_LIMIT'));

//                echo '<pre>';
//                print_r($search);
//                print_r($page);
//                print_r($limit);
//                exit();

                $params = array(
                    'logging_in_user_id' => $logging_in_user_id,
                    'search' => $search,
                    'page' => $page,
                    'limit' => $limit,
                );

                $user_connections = $this->UserConnections->getUserConnections($params);
//                pr($user_connections);
//                exit();

                $response['code'] = 200;
                $response['data'] = $user_connections;
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], array('data' => $response['data'])));
    }

}
