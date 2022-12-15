<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Address $address
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Address'), ['action' => 'edit', $address->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Address'), ['action' => 'delete', $address->id], ['confirm' => __('Are you sure you want to delete # {0}?', $address->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Addresses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Address'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Companies'), ['controller' => 'Companies', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Company'), ['controller' => 'Companies', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="addresses view large-9 medium-8 columns content">
    <h3><?= h($address->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Address Line1') ?></th>
            <td><?= h($address->address_line1) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address Line2') ?></th>
            <td><?= h($address->address_line2) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Zip Code') ?></th>
            <td><?= h($address->zip_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('City') ?></th>
            <td><?= h($address->city) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('State') ?></th>
            <td><?= h($address->state) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Country') ?></th>
            <td><?= h($address->country) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $address->has('user') ? $this->Html->link($address->user->id, ['controller' => 'Users', 'action' => 'view', $address->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Company') ?></th>
            <td><?= $address->has('company') ? $this->Html->link($address->company->id, ['controller' => 'Companies', 'action' => 'view', $address->company->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($address->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated By') ?></th>
            <td><?= $this->Number->format($address->updated_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($address->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($address->modified) ?></td>
        </tr>
    </table>
</div>
