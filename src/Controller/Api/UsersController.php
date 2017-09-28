<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['add']);
        $this->loadModel('UserAccounts');
        $this->loadModel('Masters');
        $this->loadModel('Templates');
        $this->loadModel('OtpRequests');
        $this->loadModel('UserExperience');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Countries', 'States', 'Cities', 'Roles']
        ];
        $users = $this->paginate($this->Users);

        $this->set($this->Common->apiResponse('200', 'Ok', [compact('users')]));
        // $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $id = $this->request->getParam('user_id');
        //Comparing requester with logged in user 
        if ($this->Access->getLoggedInUserId() != $id) {
            //Unauthorized user
            $this->set($this->Common->apiResponse('401'));
        } else {
            $user = $this->Users->get($id, [
                'contain' => ['Countries', 'States', 'Cities', 'Roles', 'UserAccounts', 'UserExperience']
            ]);
            $this->set($this->Common->apiResponse('200', ['user' => $user->toArray()]));
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {

        if ($this->request->is('post')) {
            $mail_template = 'user-welcome';
            if ($this->request->getData('registration_type') == 'admin-invite') {
                $mail_template = 'user-set-password';
            } else if ($this->request->getData('registration_type') == 'shared-invite') {
                $mail_template = 'user-set-password-limited';
            }

            $email_flag = 'verified';
            if ($this->request->getData('social_type') == 'linkcxo')
                $email_flag = 'un-verified';

            // Check if the mobile no is verified
            $mobile_no = $this->request->getData('mobile_no');
            $otpRequest = $this->OtpRequests->find('all')
                    ->where(['mobile_no' => $mobile_no])
                    ->toArray();

            if (empty($otpRequest) === true) {
                $this->request = $this->request->withData('flag_mobile', 'un-verified');
            } elseif ($this->request->getData('otp') == $otpRequest[0]->otp && $otpRequest[0]->otp_expire > Time::now()) {
                // OTP marked as expired immeditately after verification 
                $otpRequest = $this->OtpRequests->patchEntity($otpRequest[0], ['otp_expire' => Time::now()->modify('-1 days')]);
                if ($this->OtpRequests->save($otpRequest)) {
                    $this->request = $this->request->withData('flag_mobile', 'verified');
                } else {
                    // OTP was correct, but left un-verified as error occurred while update
                    $this->request = $this->request->withData('flag_mobile', 'un-verified');
                }
            } else {
                $this->request = $this->request->withData('flag_mobile', 'un-verified');
            }

            // Appending user role as 'User' or 'User Limited' based on invite type
            if ($this->request->getData('registration_type') == 'shared-invite') {
                $this->request = $this->request->withData('role_id', 'a903e136-7dc0-11e7-bb31-be2e44b06b34');
            } else {
                $this->request = $this->request->withData('role_id', '4dcfe7d4-c168-4d25-bee5-175f6d38059e');
            }

            $password_token = $this->Common->getRandomString(64);
            $this->request = $this->request->withData('password_token', $password_token);

            $user_accounts = [];
            $social_account = $this->Masters->find('all')
                    ->where(['type' => 'social-accounts', 'name' => $this->request->getData('social_type'), 'status' => 'active'])
                    ->toArray();
            $user_account = $this->UserAccounts->newEntity();
            $user_account_data = [
                'social_id' => $social_account[0]->uuid,
                'email' => $this->request->getData('email'),
                'social_serialize' => serialize($this->request->getData('social_serialize')),
                'token' => $this->Common->getRandomString(64),
                'flag' => $email_flag
            ];
            if ($this->request->getData('social_type') == 'linkcxo')
                $user_account_data['is_primary'] = 1;
            $user_account = $this->UserAccounts->patchEntity($user_account, $user_account_data);

            $user_accounts[] = $user_account;
            if ($this->request->getData('social_type') != 'linkcxo') {
                $social_account = $this->Masters->find('all')
                        ->where(['type' => 'social-accounts', 'name' => 'linkcxo', 'status' => 'active'])
                        ->toArray();
                $user_account = $this->UserAccounts->newEntity();
                $user_account = $this->UserAccounts->patchEntity($user_account, [
                    'social_id' => $social_account[0]->uuid,
                    'email' => $this->request->getData('email'),
                    'social_serialize' => serialize($this->request->getData('social_serialize')),
                    'token' => $this->Common->getRandomString(64),
                    'flag' => $email_flag,
                    'is_primary' => 1
                ]);
                $user_accounts[] = $user_account;
            }

            // Saving data using associations
            $user = $this->Users->newEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->user_accounts = $user_accounts;
            if ($this->Users->save($user)) {
                $templates = $this->Templates->find('all')
                        ->where(['code_name' => $mail_template, 'status' => 'active'])
                        ->toArray();
                $mail_content = $templates[0]->content;

                // Common replace parameters accross mail templates
                $mail_content = str_replace('#set_password_link#', Configure::read('SITE_DOMAIN') . '/users/' . $user->uuid . '/set-password/' . $password_token, $mail_content);

                // TODO: Custom CC BCC Templates 
                $response = $this->Common->sendMail($user_account->email, $templates[0]->subject, $mail_content);
                $this->set($this->Common->apiResponse('200', ['user' => $user->toArray()]));
            } else {
                $this->set($this->Common->apiResponse('400', $this->Common->toastErrorMessages($user->errors())));
            }
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $id = $this->request->getParam('user_id');
        //Comparing requester with logged in user 
        if ($this->Access->getLoggedInUserId() != $id) {
            //Unauthorized user
            $this->set($this->Common->apiResponse('401'));
        } else {
            $user = $this->Users->get($id, [
                'contain' => ['Countries', 'States', 'Cities', 'Roles', 'UserAccounts', 'UserExperience']
            ]);
            if ($user != null) {
//                // Any request containing 'email' as field will undergo following if block
//                if ($this->request->getData('email') != null) {
//                    $social_account = $this->Masters->find('all')
//                            ->where(['type' => 'social-accounts', 'name' => 'linkcxo', 'status' => 'active'])
//                            ->toArray();
//
//                    $whereCondition = ['user_id' => $id, 'is_primary' => 0, 'social_id' => $social_account[0]->uuid];
//                    if ($this->request->getData('detail_type') == 'primary-info')
//                        $whereCondition['is_primary'] = 1;
//
//                    $user_account = $this->UserAccounts->find('all')
//                            ->where($whereCondition)
//                            ->toArray();
//                    if (empty($user_account))
//                        $user_account = $this->UserAccounts->newEntity();
//                    else {
//                        $user_account = $user_account[0];
//                    }
//
//                    $user_account = $this->UserAccounts->patchEntity($user_account, [
//                        'social_id' => $social_account[0]->uuid,
//                        'email' => $this->request->getData('email'),
//                        'social_serialize' => serialize($this->request->getData('social_serialize')),
//                        'token' => $this->Common->getRandomString(64),
//                        'flag' => 'un-verified',
//                    ]);
//                    $user->user_accounts = [$user_account];
//                }
//
//                // Any request containing 'email' as field will undergo following if block
//                if ($this->request->getData('expDiv') != null) {
//                    $experiences = [];
//                    foreach ($this->request->getData('expDiv') as $experience) {
//                        $user_experience = $this->UserExperience->newEntity();
//                        $user_experience = $this->UserExperience->patchEntity($user_experience, $experience);
//                        $experiences[] = $user_experience;
//                    }
//                    $user->user_experience = $experiences;
//                }
//
//                $user = $this->Users->patchEntity($user, $this->request->getData());
//                if ($this->Users->save($user)) {
//                    $this->set($this->Common->apiResponse('200', ['user' => $user->toArray()]));
//                } else {
//                    $this->set($this->Common->apiResponse('400', $this->Common->toastErrorMessages($user->errors())));
//                }
            } else {
                $this->set($this->Common->apiResponse('400', 'Error fetching user details.'));
            }
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
