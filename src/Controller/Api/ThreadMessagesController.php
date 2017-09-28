<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Core\Configure;
use Cake\View\View;
use Cake\View\ViewBuilder;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * ThreadMessages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 *
 * @method \App\Model\Entity\Message[] paginate($object = null, array $settings = [])
 */
class ThreadMessagesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Messages');
        $this->loadModel('Threads');
        $this->loadModel('ThreadUsers');
        $this->loadModel('Users');
        $this->loadComponent('Common');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $response = array(
            'code' => 500,
            'data' => array(
                'messages' => array(),
                'read_receipt_users' => array(),
            ),
        );
        if ($this->request->is(['get'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $thread_id = $this->request->getParam('thread_id');
                $threadMessages = $this->Messages->find('all')
                        ->where(['thread_id' => $thread_id])
                        ->order(['created' => 'ASC'])
                        ->page(1)
                        ->limit(Configure::read('SITE_PAGINATION_LIMIT'))
                        ->toArray();
                //pr($threadMessages); exit();
                $message = end($threadMessages);

                $threadUserData = array(
                    'last_message_id' => $message->uuid,
                );
                $this->ThreadUsers->updateAll($threadUserData, ['thread_id' => $thread_id, 'user_id' => $logging_in_user_id]);


                //Last message id updated of others
                $my_last_message = "";
                foreach ($threadMessages as $threadMessage) {
                    //echo $threadMessage->created_by;
                    //echo $logging_in_user_id;
                    if ($threadMessage->created_by == $logging_in_user_id) {
                        $my_last_message = $threadMessage;
                    }
                }
                if ($my_last_message) {
                    $threadUsers = $this->ThreadUsers->find()
                            ->contain('Users')
                            ->innerJoinWith('LastMessages', function($q) use($thread_id, $my_last_message) {
                                return $q->where([
                                            'LastMessages.thread_id' => $thread_id,
                                            'LastMessages.created >=' => $my_last_message->created->format('Y-m-d H:i:s'),
                                ]);
                            })
                            ->where(['ThreadUsers.last_message_id IS NOT' => NULL, 'ThreadUsers.user_id <>' => $logging_in_user_id])
                            ->all();
                    //pr($threadUsers); exit();
                    if (!$threadUsers->isEmpty()) {
                        foreach ($threadUsers as $threadUser) {
                            $response['data']['read_receipt_users'][] = $this->Threads->formatUserData($threadUser->user);
                        }
                    }
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }
        $response['code'] = 200;
        $response['data']['messages'] = $threadMessages;

        $this->set($this->Common->apiResponse($response['code'], array('data' => $response['data'])));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $response = array(
            'code' => 500,
            'msg' => "The message could not be saved. Please, try again.",
        );
        if ($this->request->is('post')) {
            try {

                $logging_in_user_id = $this->Access->getLoggedInUserId();

                $success = false;

                $requestData = $this->request->getData();
                $requestImage = array_key_exists('image', $requestData) ? $requestData['image'] : '';
                $requestFile = array_key_exists('file', $requestData) ? $requestData['file'] : '';
//                echo '<pre>';
//                print_r($requestData);
//                print_r($_FILES);
//                print_r($requestData['user_ids']);
//                print_r($requestData['message_type']);
//                exit();
                //To save a message there are 2 scenarios;
                //1. Adding new message to a new thread (thread_id does not exists)
                //1.1. So user_ids is to be sent alongwith.
                //2. Adding new message to a old/existing thread (thread_id exists)
                //Either of thread_id or user_id should be present in the requestData
//                $user_ids = $this->request->getData('user_ids', array());
//                $thread_id = $this->request->getData('thread_id', '');
                $user_ids = isset($requestData['user_ids']) ? $requestData['user_ids'] : array();
                $thread_id = isset($requestData['thread_id']) ? $requestData['thread_id'] : '';
                if ((is_array($user_ids) && count($user_ids)) || $thread_id) {
                    if (count($user_ids)) {
                        //Scenario 1 is applicable here.
                        //Add a record in the Threads table using the first user.

                        $threadData = array(
                            'user_id' => $user_ids[0], //To whom the message is sent.
                            'header' => $requestData['header'],
                        );
//                        pr($threadData);
//                        exit();
                        $thread = $this->Threads->newEntity($threadData);
                        if ($this->Threads->save($thread)) {
                            $thread_id = $thread->uuid;
                        }
//                      else {
//                            pr($thread->errors());
//                            exit();
//                        }
                    }
                    if ($thread_id) {
//                        echo $thread_id;
//                        exit();
                        //After Scenario 1
                        //Or
                        //Directly Scenario 2 is applicable here.
                        //Add a record in the Messages table.
//                        if($requestData['message_type'] == 'image') {
//                            echo '<pre>';
//                            print_r($requestData['message']['name']);
//                            print_r($requestData['message']['tmp_name']);
//                            print_r($requestData['message']['size']);
//                            exit();
//                        }
                        $messageData = array(
                            'user_id' => $logging_in_user_id, //Sender of the message.
                            'thread_id' => $thread_id,
                            'message' => $requestData['message'],
                            //'image' => isset($requestData['image']) ? $requestData['image'] : '',
                            //'file' => isset($requestData['file']) ? $requestData['file'] : '',
                            'created_by' => $logging_in_user_id,
                        );
                        
                        $message_types = array(
                            'text' => $messageData['message'],
                            'image' => $messageData['image'],
                            'non-image' => $messageData['file']
                        );

                        $first_message = NULL;
                        $success = false;
                        foreach ($message_types as $message_type => $message_type_data) {
                            if ($message_type_data) {

                                $messageData['message_type'] = $message_type;
                                $message = $this->Messages->newEntity($messageData);
                                if ($this->Messages->save($message)) {
                                    $success = true;

                                    //Validate uploaded file
                                    if (!empty($requestImage)) {
                                        if ($requestImage['error'] == 0) {
                                            $image = array();
                                            $image['originalName'] = $requestImage['name'];

                                            $uploadPath = UPLOAD_PATH . 'messages/';

                                            $uploadFile = $uploadPath . $image['originalName'];
                                            $image['originalExt'] = pathinfo($image['originalName'])["extension"];
                                            $image['archiveName'] = (pathinfo($image['originalName'])["filename"]) . '-' . time() . "." . $image['originalExt'];

                                            if (move_uploaded_file($requestImage['tmp_name'], $uploadFile)) {
                                                $image['filesize'] = filesize($uploadFile);

                                                $messageData['image'] = serialize($image);
                                                $messageData['modified_by'] = $logging_in_user_id;
                                                $message = $this->Messages->newEntity($message, $messageData);
                                                
                                                if ($this->Messages->save($message)) {
                                                    try {
                                                        $uploadDone = $this->S3->upload($uploadFile, 'messages/' . $thread_id . '/' . $image['archiveName']);
                                                        if ($uploadDone) {
                                                            //chown(WWW_ROOT . 'uploads/' . 'posts/' . $_FILES['image_serialize']['name'],666);
                                                            //unlink(UPLOAD_PATH . 'posts/' . $_FILES['image_serialize']['name']);
                                                        }
                                                    } catch (Exception $ex) {
                                                        $this->Common->logEx(__CLASS__, __FUNCTION__, $ex);
                                                    }
                                                } 
                                            }
                                        }
                                    }
                                    else if (!empty($requestFile)){
                                        if ($requestFile['error'] == 0) {
                                            $file = array();
                                            $file['originalName'] = $requestFile['name'];

                                            $uploadPath = UPLOAD_PATH . 'messages/';

                                            $uploadFile = $uploadPath . $file['originalName'];
                                            $file['originalExt'] = pathinfo($file['originalName'])["extension"];
                                            $file['archiveName'] = (pathinfo($file['originalName'])["filename"]) . '-' . time() . "." . $file['originalExt'];

                                            if (move_uploaded_file($requestFile['tmp_name'], $uploadFile)) {
                                                $file['filesize'] = filesize($uploadFile);

                                                $messageData['image'] = serialize($file);
                                                $messageData['modified_by'] = $logging_in_user_id;
                                                $message = $this->Messages->newEntity($message, $messageData);
                                                //$group = $this->Groups->patchEntity($group, $groupData);
                                                if ($this->Messages->save($message)) {
                                                    try {
                                                        $uploadDone = $this->S3->upload($uploadFile, 'messages/' . $thread_id . '/' . $file['archiveName']);
                                                        if ($uploadDone) {
                                                            //chown(WWW_ROOT . 'uploads/' . 'posts/' . $_FILES['image_serialize']['name'],666);
                                                            //unlink(UPLOAD_PATH . 'posts/' . $_FILES['image_serialize']['name']);
                                                        }
                                                    } catch (Exception $ex) {
                                                        $this->Common->logEx(__CLASS__, __FUNCTION__, $ex);
                                                    }
                                                } 
                                            }
                                        }
                                    }
                                        



                                    if (!$first_message) {
                                        $first_message = $message;
                                    }
                                }
                            }
                        }

                        if ($success) {
                            //Check if this is a new thread ($user_id was passed in the request)
                            if (count($user_ids)) {
//                                echo 'a';
//                                exit();
                                //New thread
                                //Add new entries for both the Users in the ThreadUsers table.
                                //First, the sender to be saved.
                                $threadUserData = array(
                                    'user_id' => $logging_in_user_id, //Sender of the message.
                                    'thread_id' => $thread_id,
                                    'start_message_id' => $first_message->uuid,
                                    'last_message_id' => $message->uuid,
                                    'status' => 'active',
                                );
                                $thread_user = $this->ThreadUsers->newEntity($threadUserData);
                                if ($this->ThreadUsers->save($thread_user)) {
                                    //Now the receivers of the message to be saved.
                                    foreach ($user_ids as $user_id) {
                                        $threadUserData = array(
                                            'user_id' => $user_id, //Sender of the message.
                                            'thread_id' => $thread_id,
                                            'start_message_id' => $first_message->uuid,
                                            'status' => 'active',
                                        );
                                        $thread_user = $this->ThreadUsers->newEntity($threadUserData);
                                        if ($this->ThreadUsers->save($thread_user)) {
                                            $success = true;
                                        }
                                    }
                                }
                            } else {
                                //Old Thread
                                //Update the sender's record in the ThreadUsers table.
                                $threadUserData = array(
                                    'last_message_id' => $message->uuid,
                                );
                                if ($this->ThreadUsers->updateAll($threadUserData, ['user_id' => $logging_in_user_id, 'thread_id' => $thread_id])) {
                                    $success = true;
                                }

                                //Update all the newly added users in this thread with start_message_id as the above added message.
                                //So that all those users can now see messages from this message onwards.
                                $threadUserData = array(
                                    'start_message_id' => $first_message->uuid,
                                );
                                if ($this->ThreadUsers->updateAll($threadUserData, ['thread_id' => $thread_id, 'start_message_id IS NULL'])) {
                                    $success = true;
                                }
                            }
                        } else {
                            $response['code'] = 400;
                            $response['msg'] = $this->Common->toastErrorMessages($message->errors());
                        }
                    } else {
                        $response['code'] = 400;
                    }
                } else {
                    $response['code'] = 400;
                }

                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "The message has been saved.";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }

        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }

    public function export() {
        $response = array(
            'code' => 500,
            'msg' => "The messages could not be exported. Please, try again.",
        );
        if ($this->request->is(['get'])) {
            try {
                $logging_in_user_id = $this->Access->getLoggedInUserId();
//                $user = $this->Users->get($logging_in_user_id);
//                             ->matching('Users', function($q) use($logging_in_user_id) {
//                               return $q->where(['user_id' => $logging_in_user_id]);
                //$user->email;
//               echo $user;
//               exit();

                $success = false;

                $thread_id = $this->request->getParam('thread_id');

                // Your data array
                $threadMessages = $this->Messages->find('all')
                        ->where(['thread_id' => $thread_id])
                        ->page(1)
                        ->limit(Configure::read('SITE_PAGINATION_LIMIT'))
                        ->toArray();


                //if ($this->request->params['_ext'] === 'csv') {
                // Params
                $_serialize = 'threadMessages';

                // Create the builder
                $builder = new ViewBuilder;
                $builder->layout = false;
                $builder->setClassName('CsvView.Csv');

                // Then the view
                $view = $builder->build($threadMessages);
                $view->set(compact('threadMessages', '_serialize'));

                // And Save the file
                $file = new File('./files/message.csv', true, 0644);
                $file->write($view->render());

                $this->response->download('message.csv');
                $this->Common->sendMail('rohan.vakil@v2solutions.com', 'CSV Export', '', [], [], ['./files/message.csv']);


                // And Download the file
                //$this->response->download('my_file.csv');

                $success = true;
                //}
                if ($success) {
                    $response['code'] = 200;
                    $response['msg'] = "The messages have been exported.";
                }
            } catch (Exception $ex) {
                $response['code'] = 500;
            }
        } else {
            $response['code'] = 500;
        }
        $this->set($this->Common->apiResponse($response['code'], $response['msg']));
    }

}
