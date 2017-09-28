<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
use Firebase\JWT\JWT;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Overtrue\Socialite\SocialiteManager;
use Overtrue\Socialite\AccessToken;
use Symfony\Component\HttpFoundation\Request;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AuthController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['login', 'setOtp', 'setPassword', 'forgotPassword', 'getSocialDetails']);
        $this->loadModel('OtpRequests');
        $this->loadModel('UserAccounts');
        $this->loadModel('Users');
        $this->loadModel('Templates');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
    }

    public function setOtp() {
        if ($this->request->is('put')) {

            $mobile_no = $this->request->getData('mobile_no');

            //==============================================================
            //####### CODE TO GENERATE OTP HERE #########
            //==============================================================

            $otp = '123456';

            $otpRequest = $this->OtpRequests->find('all')
                    ->where(['mobile_no' => $mobile_no])
                    ->toArray();

            $this->request = $this->request->withData('otp', $otp);
            // By default OTP is set to expire after 24 hours
            $this->request = $this->request->withData('otp_expire', Time::now()->modify('+1 days'));
            if (empty($otpRequest) === true) {
                $this->request = $this->request->withData('counter', 1);
                $otpRequest = $this->OtpRequests->newEntity();
            } else {
                $otpRequest = $otpRequest[0];
                $this->request = $this->request->withData('counter', $otpRequest->counter + 1);
            }

            $otpRequest = $this->OtpRequests->patchEntity($otpRequest, $this->request->getData());
            if ($this->OtpRequests->save($otpRequest)) {
                $this->set($this->Common->apiResponse('200', ['otp_request' => $otpRequest->toArray()]));
            } else {
                $this->set($this->Common->apiResponse('400', $this->Common->toastErrorMessages($otpRequest->errors())));
            }
        }
    }

    public function login() {
        // as found on https://stackoverflow.com/questions/33924163/cakephp-3-x-admad-jwtauth-doesnt-work
        $email = $this->request->getData('email');
        $pwd = $this->request->getData('password');

        $query = $this->UserAccounts->find('all', ['contain' => ['Users']]);
        $user_account = $query->where(['OR' => ['email' => $email, 'Users.mobile_no' => $email, 'Users.alt_mobile' => $email]], ['Users.status' => 'active'])->first();

        $token = null;
        if ($user_account != null && (new DefaultPasswordHasher)->check($pwd, $user_account->user->password)) {
            $token = JWT::encode([
                        'id' => $user_account->user->uuid,
                        'sub' => $user_account->user->uuid,
                        'exp' => time() + 604800,
                        'user' => $user_account->user
                            ], Security::salt());
            $this->set($this->Common->apiResponse('200', array('data' => ['token' => $token])));
        } else {
            $this->set($this->Common->apiResponse('401', array('data' => ['token' => $token])));
        }
    }

    public function setPassword() {
        if ($this->request->is('put')) {
            $request_type = $this->request->getParam('request_type');
            $this->request = $this->request->withData('request_type', $request_type);

            $user_id = $this->request->getParam('user_id');

            $user = $this->Users->get($user_id, [
                'contain' => ['Roles', 'UserAccounts']
            ]);

            $this->request = $this->request->withData('password', $this->request->getData('new_password'));
            //reset password token
            $this->request = $this->request->withData('password_token', '');
            $this->request = $this->request->withData('old_password_token', $user->password_token);
            $this->request = $this->request->withData('new_password_token', $this->request->getParam('password_token'));

            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->set($this->Common->apiResponse('200', ['user' => $user->toArray()]));
            } else {
                $this->set($this->Common->apiResponse('400', $this->Common->toastErrorMessages($user->errors())));
            }
        }
    }

    public function forgotPassword() {
        if ($this->request->is('put')) {
            $email = $this->request->getData('email');

            $query = $this->UserAccounts->find('all', ['contain' => ['Users']]);
            $user_account = $query->where(['email' => $email], ['Users.status' => 'active'])->first();

            if ($user_account !== null) {
                $password_token = $this->Common->getRandomString(64);
                $user_account->user->password_token = $password_token;
                $this->Users->save($user_account->user);
                $templates = $this->Templates->find('all')
                        ->where(['code_name' => 'forgot-password', 'status' => 'active'])
                        ->toArray();
                $mail_content = $templates[0]->content;

                // Common replace parameters accross mail templates
                $mail_content = str_replace('#forgot-password-link#', Configure::read('SITE_DOMAIN') . '/users/' . $user_account->user->uuid . '/set-password/' . $password_token, $mail_content);

                // TODO: Custom CC BCC Templates 
                $response = $this->Common->sendMail($user_account->email, $templates[0]->subject, $mail_content);
                $this->set($this->Common->apiResponse('200', ['user_account' => $user_account->toArray()]));
            } else {
                $this->set($this->Common->apiResponse('400', 'Unable to identify registered email address.'));
            }
        }
    }

    public function getSocialDetails() {
        $config = [
            'google' => [
                'client_id' => '1060369273388-j8394g2hbt09s42r7js1keslq5cqlgg2.apps.googleusercontent.com',
                'client_secret' => 'KqtPpBml7KBPhypq_t0FWbp-',
                'redirect' => 'http://dev.linkcxo.com/login',
            ],
        ];

        $socialite = new SocialiteManager($config);
        $req = new Request(['code' => '4/PEVZX23e71XwXMLfRKS0ymrAlnL5nw7_mnZW4md90M4']);
        $socialite->setRequest($req);
        $googleDriver = $socialite->driver('google');
        $googleDriver->stateless();
        $user = $googleDriver->user();
        pr($user);
    }

}
