<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List User Experience'), ['action' => 'index']) ?></li>
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
<div class="userExperience form large-9 medium-8 columns content">
    <?= $this->Form->create($userExperience) ?>
    <fieldset>
        <legend><?= __('Add User Experience') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('emp_name');
            echo $this->Form->control('from_date');
            echo $this->Form->control('to_date');
            echo $this->Form->control('till_now');
            echo $this->Form->control('role');
            echo $this->Form->control('country_id', ['options' => $countries]);
            echo $this->Form->control('state_id', ['options' => $states]);
            echo $this->Form->control('city_id', ['options' => $cities]);
            echo $this->Form->control('industry_id');
            echo $this->Form->control('functional_area_id');
            echo $this->Form->control('designation_id');
            echo $this->Form->control('achievement');
            echo $this->Form->control('short_description');
            echo $this->Form->control('created_by');
            echo $this->Form->control('modified_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
