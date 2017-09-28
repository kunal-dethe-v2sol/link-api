<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $userConnection->uuid],
                ['confirm' => __('Are you sure you want to delete # {0}?', $userConnection->uuid)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List User Connections'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="userConnections form large-9 medium-8 columns content">
    <?= $this->Form->create($userConnection) ?>
    <fieldset>
        <legend><?= __('Edit User Connection') ?></legend>
        <?php
            echo $this->Form->control('user_id');
            echo $this->Form->control('connection_id', ['options' => $users]);
            echo $this->Form->control('status');
            echo $this->Form->control('created_by');
            echo $this->Form->control('modified_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
