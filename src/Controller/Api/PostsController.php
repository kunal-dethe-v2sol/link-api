<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * Posts Controller
 *
 * @property \App\Model\Table\PostsTable $Posts
 *
 * @method \App\Model\Entity\Post[] paginate($object = null, array $settings = [])
 */
class PostsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
        $this->loadComponent('Common');
        $this->loadComponent('S3');
        $this->loadModel('PostSharewithGroups');
        $this->loadModel('PostSharewithEmail');
        $this->loadModel('PostSharewithPeople');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     *  API - GET METHOD
     */
    public function index() {
        $this->paginate = [
                //'contain' => ['Users', 'Groups']
        ];
        $posts = $this->paginate($this->Posts);
        $this->set(compact('posts'));
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * API - GET METHOD USING ID
     */
    public function view($id = null) {
        $post = $this->Posts->get($id);
        $this->set('post', $post);
        $this->set('_serialize', ['post']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     * API - POST METHOD
     */
    public function add() {

        $post = $this->Posts->newEntity();
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();

            if (!empty($_FILES['image_serialize']['name'])) {
                /**
                 * Save image to S3 BUCKET  and serialized image array in db.
                 *
                 */
                $fileArray[] = '';
                $fileArray['originalName'] = $_FILES['image_serialize']['name'];
                $uploadPath = UPLOAD_PATH . 'posts/';
                $uploadFile = $uploadPath . $fileArray['originalName'];

                $fileArray['originalExt'] = pathinfo($fileArray['originalName'])["extension"];
                $fileArray['archiveName'] = (pathinfo($fileArray['originalName'])["filename"]) . '-' . time() . "." . $fileArray['originalExt'];

                if (move_uploaded_file($_FILES['image_serialize']['tmp_name'], $uploadFile)) {

                    $fileArray['filesize'] = filesize($uploadFile);

                    $requestData['user_id'] = "14e5e24d-bfbb-4e74-812f-a41467c6954f";
                    $requestData['image_serialize'] = serialize($fileArray);

                    $post = $this->Posts->patchEntity($post, $requestData);
                    if ($this->Posts->save($post)) {
                        try {

                            $postUuid = $post->uuid;
                            $uploadDone = $this->S3->upload($uploadFile, 'posts/' . $postUuid . '/' . $fileArray['archiveName']);
                            if ($uploadDone) {
                                //chown(WWW_ROOT . 'uploads/' . 'posts/' . $_FILES['image_serialize']['name'],666);
                                //unlink(UPLOAD_PATH . 'posts/' . $_FILES['image_serialize']['name']);
                            }
                        } catch (Exception $ex) {
                            $this->Common->logException(__CLASS__, __FUNCTION__, $ex->getMessage(), $ex->getCode(), $ex->getLine(), date('Y-m-d'));
                        }

                        $this->set($this->Common->apiResponse("200", "The post has been saved."));
                    } else {
                        $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($post->errors())));
                    }
                } else {
                    $this->set($this->Common->apiResponse("400", "Unable to upload file, please try again."));
                }
            } else {
                $this->set($this->Common->apiResponse("400", "Please choose a file to upload."));
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     * API - PUT METHOD USING ID
     */
    public function edit($id = null) {
        $post = $this->Posts->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestData = $this->request->getData();
            pr($_REQUEST);
            exit;
            $requestData['user_id'] = "14e5e24d-bfbb-4e74-812f-a41467c6954f";

            if (!empty($_FILES['image_serialize']['name'])) {
                $fileArray[] = '';
                $fileArray['originalName'] = $_FILES['image_serialize']['name'];
                $uploadPath = UPLOAD_PATH . 'posts/';
                $uploadFile = $uploadPath . $fileArray['originalName'];

                $fileArray['originalExt'] = pathinfo($fileArray['originalName'])["extension"];
                $fileArray['archiveName'] = (pathinfo($fileArray['originalName'])["filename"]) . '-' . time() . "." . $fileArray['originalExt'];

                if (move_uploaded_file($_FILES['image_serialize']['tmp_name'], $uploadFile)) {

                    $fileArray['filesize'] = filesize($uploadFile);

                    $requestData['image_serialize'] = serialize($fileArray);

                    try {
                        $postUuid = $post['uuid'];
                        $this->S3->upload($uploadFile, 'posts/' . $postUuid . '/' . $fileArray['archiveName']);
                        // unlink(UPLOAD_PATH . 'posts/' . $_FILES['image_serialize']['name']);
                    } catch (Exception $ex) {
                        $this->Common->logException(__CLASS__, __FUNCTION__, $ex->getMessage(), $ex->getCode(), $ex->getLine(), date('Y-m-d'));
                    }
                } else {
                    $this->set($this->Common->apiResponse("400", "Unable to upload file, please try again."));
                }
            }

            $post = $this->Posts->patchEntity($post, $requestData);
            if ($this->Posts->save($post)) {

                $this->set($this->Common->apiResponse("200", "The post has been updated."));
            } else {
                $this->set($this->Common->apiResponse("400", $this->Common->toastErrorMessages($post->errors())));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * API - DELETE METHOD USING ID
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $post = $this->Posts->get($id);
        $imageArr = unserialize($post['image_serialize']);
        $filename = $imageArr['archiveName'];

        if ($this->Posts->delete($post)) {
            try {
                //$this->S3->bucket = "spheric-trailers";
                $this->S3->delete(array('posts/' . $post['uuid'] . '/' . $filename));
            } catch (Exception $ex) {
                $this->Common->logException(__CLASS__, __FUNCTION__, $ex->getMessage(), $ex->getCode(), $ex->getLine(), date('Y-m-d'));
            }
            $this->set($this->Common->apiResponse("200", "The post has been deleted."));
        } else {
            $this->set($this->Common->apiResponse("404", "The post could not be deleted. Please, try again."));
        }
    }

    public function sharepost() {

        $post_id = $this->request->getParam('post_id');
        if ($this->request->is('post')) {
            $requestData = $this->request->getData();
            
            /*
             * Save Data for share post with email
             */
            if (isset($requestData['share_email']) and ! empty($requestData['share_email'][0])) {
                $share_emails = explode(',', $requestData['share_email'][0]);
                foreach ($share_emails as $key => $value) {
                    $saveArray = array(
                        'user_id' => '14e5e24d-bfbb-4e74-812f-a41467c6954f', /*                         * #TODO - UPDATED LATER */
                        'post_id' => $post_id,
                        'email' => $value,
                        'url' => '', /*                             * #TODO - CREATE ACCESS LOGIN URL */
                    );                    
                    $postSharewithEmail = $this->PostSharewithEmail->newEntity();
                    $postSharewithEmail = $this->PostSharewithEmail->patchEntity($postSharewithEmail, $saveArray);
                    if ($this->PostSharewithEmail->save($postSharewithEmail)) {
                        /*
                         * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                         */
                    }
                }
            }
            
            /*
             * Save Data for share post with CXO connections
             */
            if (isset($requestData['share_connections']) and ! empty($requestData['share_connections'][0])) {
                $share_connections = explode(',', $requestData['share_connections'][0]);
                foreach ($share_connections as $key => $value) {
                    $saveArray = array(
                        'user_id' => '14e5e24d-bfbb-4e74-812f-a41467c6954f', /*                         * #TODO - UPDATED LATER */
                        'post_id' => $post_id,
                        'person_id' => $value,
                    );                    
                    $postSharewithPerson = $this->PostSharewithPeople->newEntity();
                    $postSharewithPerson = $this->PostSharewithPeople->patchEntity($postSharewithPerson, $saveArray);
                    
                    if ($this->PostSharewithPeople->save($postSharewithPerson)) {
                        /*
                         * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                         */
                    }                    
                }
            }
            
            /*
             * Save Data for share post with groups
             */
            if (isset($requestData['share_groups']) and ! empty($requestData['share_groups'][0])) {
                $share_groups = explode(',', $requestData['share_groups'][0]);
                foreach ($share_groups as $key => $value) {
                    $saveArray = array(
                        'user_id' => '14e5e24d-bfbb-4e74-812f-a41467c6954f', /*                         * #TODO - UPDATED LATER */
                        'post_id' => $post_id,
                        'group_id' => $value,
                    );
                    $postSharewithGroup = $this->PostSharewithGroups->newEntity();
                    $postSharewithGroup = $this->PostSharewithGroups->patchEntity($postSharewithGroup, $saveArray);
                    if ($this->PostSharewithGroups->save($postSharewithGroup)) {
                        /*
                         * #TODO - AFTER SAVE [NOTIFY &  EMAIL]
                         */
                    }
                }
            }
            $this->set($this->Common->apiResponse("200", "Post shared successfully"));
        }
    }

}
