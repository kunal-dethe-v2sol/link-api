<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\UserConnection[]|\Cake\Collection\CollectionInterface $userConnections
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User Connection'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="userConnections index large-9 medium-8 columns content">
    <h3><?= __('User Connections') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('uuid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('connection_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified_by') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userConnections as $userConnection): ?>
            <tr>
                <td><?= h($userConnection->uuid) ?></td>
                <td><?= h($userConnection->user_id) ?></td>
                <td><?= $userConnection->has('user') ? $this->Html->link($userConnection->user->uuid, ['controller' => 'Users', 'action' => 'view', $userConnection->user->uuid]) : '' ?></td>
                <td><?= h($userConnection->status) ?></td>
                <td><?= h($userConnection->created) ?></td>
                <td><?= $this->Number->format($userConnection->created_by) ?></td>
                <td><?= h($userConnection->modified) ?></td>
                <td><?= h($userConnection->modified_by) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $userConnection->uuid]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $userConnection->uuid]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $userConnection->uuid], ['confirm' => __('Are you sure you want to delete # {0}?', $userConnection->uuid)]) ?>
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
