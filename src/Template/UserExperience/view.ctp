<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\UserExperience $userExperience
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User Experience'), ['action' => 'edit', $userExperience->uuid]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User Experience'), ['action' => 'delete', $userExperience->uuid], ['confirm' => __('Are you sure you want to delete # {0}?', $userExperience->uuid)]) ?> </li>
        <li><?= $this->Html->link(__('List User Experience'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Experience'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List States'), ['controller' => 'States', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New State'), ['controller' => 'States', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cities'), ['controller' => 'Cities', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New City'), ['controller' => 'Cities', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="userExperience view large-9 medium-8 columns content">
    <h3><?= h($userExperience->uuid) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Uuid') ?></th>
            <td><?= h($userExperience->uuid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $userExperience->has('user') ? $this->Html->link($userExperience->user->uuid, ['controller' => 'Users', 'action' => 'view', $userExperience->user->uuid]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Emp Name') ?></th>
            <td><?= h($userExperience->emp_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Till Now') ?></th>
            <td><?= h($userExperience->till_now) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Role') ?></th>
            <td><?= h($userExperience->role) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= $userExperience->has('country') ? $this->Html->link($userExperience->country->name, ['controller' => 'Countries', 'action' => 'view', $userExperience->country->uuid]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('State') ?></th>
            <td><?= $userExperience->has('state') ? $this->Html->link($userExperience->state->name, ['controller' => 'States', 'action' => 'view', $userExperience->state->uuid]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('City') ?></th>
            <td><?= $userExperience->has('city') ? $this->Html->link($userExperience->city->name, ['controller' => 'Cities', 'action' => 'view', $userExperience->city->uuid]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Industry Id') ?></th>
            <td><?= h($userExperience->industry_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Functional Area Id') ?></th>
            <td><?= h($userExperience->functional_area_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Designation Id') ?></th>
            <td><?= h($userExperience->designation_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Achievement') ?></th>
            <td><?= h($userExperience->achievement) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Short Description') ?></th>
            <td><?= h($userExperience->short_description) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= h($userExperience->created_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified By') ?></th>
            <td><?= h($userExperience->modified_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('From Date') ?></th>
            <td><?= h($userExperience->from_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('To Date') ?></th>
            <td><?= h($userExperience->to_date) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($userExperience->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($userExperience->modified) ?></td>
        </tr>
    </table>
</div>
