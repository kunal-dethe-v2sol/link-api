<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\UserConnection $userConnection
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User Connection'), ['action' => 'edit', $userConnection->uuid]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User Connection'), ['action' => 'delete', $userConnection->uuid], ['confirm' => __('Are you sure you want to delete # {0}?', $userConnection->uuid)]) ?> </li>
        <li><?= $this->Html->link(__('List User Connections'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User Connection'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="userConnections view large-9 medium-8 columns content">
    <h3><?= h($userConnection->uuid) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Uuid') ?></th>
            <td><?= h($userConnection->uuid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User Id') ?></th>
            <td><?= h($userConnection->user_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $userConnection->has('user') ? $this->Html->link($userConnection->user->uuid, ['controller' => 'Users', 'action' => 'view', $userConnection->user->uuid]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($userConnection->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified By') ?></th>
            <td><?= h($userConnection->modified_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By') ?></th>
            <td><?= $this->Number->format($userConnection->created_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($userConnection->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($userConnection->modified) ?></td>
        </tr>
    </table>
</div>
