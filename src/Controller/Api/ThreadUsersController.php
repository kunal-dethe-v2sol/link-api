<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * ThreadUsers Controller
 *
 * @property \App\Model\Table\ThreadUsersTable $ThreadUsers
 *
 * @method \App\Model\Entity\ThreadUser[] paginate($object = null, array $settings = [])
 */
class ThreadUsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['add', 'delete']);
        $this->loadModel('Threads');
        $this->loadModel('ThreadUsers');
        $this->loadComponent('Common');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $response = array(
            'code' => 500,
            'msg' => "The user could not be added to the thread. Please, try again.",
        );
        if ($this->request->is('post')) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $thread_id = $this->request->getParam('thread_id');
                $user_ids = $this->request->getData('user_ids');

//                pr($user_ids);
//                exit();

                foreach ($user_ids as $user_id) {
                    //Add user to the thread
                    $threadUserData = array(
                        'user_id' => $user_id,
                        'thread_id' => $thread_id,
                        'start_message_id' => NULL,
                        'last_message_id' => NULL,
                        'status' => 'active',
                    );
                    $threadUser = $this->ThreadUsers->newEntity($threadUserData);
                    if ($this->ThreadUsers->save($threadUser)) {
                        $success = true;
                    } else {
                        $response['code'] = 400;
                        $response['msg'] = $this->Common->toastErrorMessages($threadUser->errors());
                    }
                }
                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "The user is successfully added to the thread.";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        }
        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }
}
