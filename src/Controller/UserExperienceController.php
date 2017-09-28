<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserExperience Controller
 *
 * @property \App\Model\Table\UserExperienceTable $UserExperience
 *
 * @method \App\Model\Entity\UserExperience[] paginate($object = null, array $settings = [])
 */
class UserExperienceController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Countries', 'States', 'Cities', 'Industries', 'FunctionalAreas', 'Designations']
        ];
        $userExperience = $this->paginate($this->UserExperience);

        $this->set(compact('userExperience'));
        $this->set('_serialize', ['userExperience']);
    }

    /**
     * View method
     *
     * @param string|null $id User Experience id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userExperience = $this->->get($id, [
            'contain' => ['Users', 'Countries', 'States', 'Cities', 'Industries', 'FunctionalAreas', 'Designations']
        ]);

        $this->set('userExperience', $userExperience);
        $this->set('_serialize', ['userExperience']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userExperience = $this->UserExperience->newEntity();
        if ($this->request->is('post')) {
            $userExperience = $this->UserExperience->patchEntity($userExperience, $this->request->getData());
            if ($this->UserExperience->save($userExperience)) {
                $this->Flash->success(__('The user experience has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user experience could not be saved. Please, try again.'));
        }
        $users = $this->UserExperience->Users->find('list', ['limit' => 200]);
        $countries = $this->UserExperience->Countries->find('list', ['limit' => 200]);
        $states = $this->UserExperience->States->find('list', ['limit' => 200]);
        $cities = $this->UserExperience->Cities->find('list', ['limit' => 200]);
        $industries = $this->UserExperience->Industries->find('list', ['limit' => 200]);
        $functionalAreas = $this->UserExperience->FunctionalAreas->find('list', ['limit' => 200]);
        $designations = $this->UserExperience->Designations->find('list', ['limit' => 200]);
        $this->set(compact('userExperience', 'users', 'countries', 'states', 'cities', 'industries', 'functionalAreas', 'designations'));
        $this->set('_serialize', ['userExperience']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Experience id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userExperience = $this->UserExperience->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userExperience = $this->UserExperience->patchEntity($userExperience, $this->request->getData());
            if ($this->UserExperience->save($userExperience)) {
                $this->Flash->success(__('The user experience has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user experience could not be saved. Please, try again.'));
        }
        $users = $this->UserExperience->Users->find('list', ['limit' => 200]);
        $countries = $this->UserExperience->Countries->find('list', ['limit' => 200]);
        $states = $this->UserExperience->States->find('list', ['limit' => 200]);
        $cities = $this->UserExperience->Cities->find('list', ['limit' => 200]);
        $industries = $this->UserExperience->Industries->find('list', ['limit' => 200]);
        $functionalAreas = $this->UserExperience->FunctionalAreas->find('list', ['limit' => 200]);
        $designations = $this->UserExperience->Designations->find('list', ['limit' => 200]);
        $this->set(compact('userExperience', 'users', 'countries', 'states', 'cities', 'industries', 'functionalAreas', 'designations'));
        $this->set('_serialize', ['userExperience']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Experience id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userExperience = $this->UserExperience->get($id);
        if ($this->UserExperience->delete($userExperience)) {
            $this->Flash->success(__('The user experience has been deleted.'));
        } else {
            $this->Flash->error(__('The user experience could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
