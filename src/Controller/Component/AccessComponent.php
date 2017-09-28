<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Mailer\Email;

class AccessComponent extends Component {

    public $components = ['Auth'];

    public function initialize(array $config) {
        
    }

    public function getLoggedInUserId() {
        return $this->Auth->user()['uuid'];
    }

}
