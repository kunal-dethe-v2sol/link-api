<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\UserExperience[]|\Cake\Collection\CollectionInterface $userExperience
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User Experience'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Countries'), ['controller' => 'Countries', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Country'), ['controller' => 'Countries', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List States'), ['controller' => 'States', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New State'), ['controller' => 'States', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cities'), ['controller' => 'Cities', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New City'), ['controller' => 'Cities', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="userExperience index large-9 medium-8 columns content">
    <h3><?= __('User Experience') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('uuid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('emp_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('from_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('to_date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('till_now') ?></th>
                <th scope="col"><?= $this->Paginator->sort('role') ?></th>
                <th scope="col"><?= $this->Paginator->sort('country_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('state_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('city_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('industry_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('functional_area_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('designation_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('achievement') ?></th>
                <th scope="col"><?= $this->Paginator->sort('short_description') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified_by') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userExperience as $userExperience): ?>
            <tr>
                <td><?= h($userExperience->uuid) ?></td>
                <td><?= $userExperience->has('user') ? $this->Html->link($userExperience->user->uuid, ['controller' => 'Users', 'action' => 'view', $userExperience->user->uuid]) : '' ?></td>
                <td><?= h($userExperience->emp_name) ?></td>
                <td><?= h($userExperience->from_date) ?></td>
                <td><?= h($userExperience->to_date) ?></td>
                <td><?= h($userExperience->till_now) ?></td>
                <td><?= h($userExperience->role) ?></td>
                <td><?= $userExperience->has('country') ? $this->Html->link($userExperience->country->name, ['controller' => 'Countries', 'action' => 'view', $userExperience->country->uuid]) : '' ?></td>
                <td><?= $userExperience->has('state') ? $this->Html->link($userExperience->state->name, ['controller' => 'States', 'action' => 'view', $userExperience->state->uuid]) : '' ?></td>
                <td><?= $userExperience->has('city') ? $this->Html->link($userExperience->city->name, ['controller' => 'Cities', 'action' => 'view', $userExperience->city->uuid]) : '' ?></td>
                <td><?= h($userExperience->industry_id) ?></td>
                <td><?= h($userExperience->functional_area_id) ?></td>
                <td><?= h($userExperience->designation_id) ?></td>
                <td><?= h($userExperience->achievement) ?></td>
                <td><?= h($userExperience->short_description) ?></td>
                <td><?= h($userExperience->created) ?></td>
                <td><?= h($userExperience->created_by) ?></td>
                <td><?= h($userExperience->modified) ?></td>
                <td><?= h($userExperience->modified_by) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $userExperience->uuid]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $userExperience->uuid]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $userExperience->uuid], ['confirm' => __('Are you sure you want to delete # {0}?', $userExperience->uuid)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
