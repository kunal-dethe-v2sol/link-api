<?php

namespace App\Controller\Api;

use App\Controller\Api\AppController;

/**
 * UserAccounts Controller
 *
 * @property \App\Model\Table\UserAccountsTable $UserAccounts
 *
 * @method \App\Model\Entity\UserAccount[] paginate($object = null, array $settings = [])
 */
class UserAccountsController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $userAccounts = $this->paginate($this->UserAccounts);

        $this->set(compact('userAccounts'));
        $this->set('_serialize', ['userAccounts']);
    }

    /**
     * View method
     *
     * @param string|null $id User Account id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $userAccount = $this->UserAccounts->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('userAccount', $userAccount);
        $this->set('_serialize', ['userAccount']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $userAccount = $this->UserAccounts->newEntity();
        if ($this->request->is('post')) {
            $userAccount = $this->UserAccounts->patchEntity($userAccount, $this->request->getData());
            if ($this->UserAccounts->save($userAccount)) {
                $this->Flash->success(__('The user account has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user account could not be saved. Please, try again.'));
        }
        $users = $this->UserAccounts->Users->find('list', ['limit' => 200]);
        $this->set(compact('userAccount', 'users'));
        $this->set('_serialize', ['userAccount']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Account id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $userAccount = $this->UserAccounts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userAccount = $this->UserAccounts->patchEntity($userAccount, $this->request->getData());
            if ($this->UserAccounts->save($userAccount)) {
                $this->Flash->success(__('The user account has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user account could not be saved. Please, try again.'));
        }
        $users = $this->UserAccounts->Users->find('list', ['limit' => 200]);
        $this->set(compact('userAccount', 'users'));
        $this->set('_serialize', ['userAccount']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Account id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $userAccount = $this->UserAccounts->get($id);
        if ($this->UserAccounts->delete($userAccount)) {
            $this->Flash->success(__('The user account has been deleted.'));
        } else {
            $this->Flash->error(__('The user account could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
