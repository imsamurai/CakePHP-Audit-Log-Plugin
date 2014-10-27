<?php
/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Dec 23, 2013
 * Time: 5:50:39 PM
 * Format: http://book.cakephp.org/2.0/en/views.html
 * 
 * @package Localization.View.Element
 */
/* @var $this View */

echo $this->Form->create('Audit', array(
	'novalidate',
	'class' => 'well form-search',
	'type' => 'get',
	'url' => array(
		'action' => 'index',
		'controller' => 'audit'
	)
));
?>
<div style="float:left;width:400px;margin-right:15px;">
	<div class="div-right">
		<?= $this->Form->input('id', array('type' => 'text', 'class' => 'input-xlarge')); ?>
	</div>
	<div class="div-right">
		<?=
		$this->Form->input('event', array(
			'options' => array(
				'CREATE' => 'CREATE',
				'DELETE' => 'DELETE',
				'EDIT' => 'EDIT'
			),
			'type' => 'select',
			'multiple' => true
		));
		?>
	</div>
	<div class="div-right">
		<?= $this->Form->input('model', array('class' => 'input-large')); ?>
	</div>
</div>
<div style="float:left;width:300px;">
	<div class="div-right">
		<?= $this->Form->input('created', array('class' => 'input-large daterangepicker', 'type' => 'text')); ?>
	</div>
	<div class="div-right">
		<?= $this->Form->input('user_id', array(
			'options' => $users,
			'type' => 'select',
			'multiple' => true,
			'label' => __('Users')
		)); ?>
	</div>
	<div class="div-right">
		<?= $this->Form->input('entity_id', array('type' => 'text', 'class' => 'input-xlarge')); ?>
	</div>
</div>
<div style="clear:left;"></div>
<div style="float:left;width:415px;">
	<div class="div-right">
		<?= $this->Form->button(__('Search'), array('class' => 'btn btn-primary', 'div' => false)); ?>
		<?= $this->Html->link(__('Clear'), array('action' => 'index'), array('class' => 'btn', 'id' => "btn-clear")); ?>
	</div>
</div>
<div style="clear:left;"></div>
<?php
echo $this->Form->end();
