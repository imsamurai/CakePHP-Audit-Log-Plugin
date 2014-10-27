<?php
/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:51:06
 * Format: http://book.cakephp.org/2.0/en/views.html
 *
 * @package AuditLog
 * @subpackage View
 */
/* @var $this View */
?>
<h1>Audits list</h1>
<?php
echo $this->element('form/audit/search');
echo $this->element('pagination/pagination');
echo $this->Form->create('Task', array('type' => 'get', 'class' => 'batch-form', 'url' => array('action' => 'batch', 'controller' => 'task')));
?>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th><?= $this->Paginator->sort('id'); ?></th>
			<th><?= $this->Paginator->sort('event'); ?></th>
			<th><?= $this->Paginator->sort('model'); ?></th>
			<th><?= $this->Paginator->sort('entity_id'); ?></th>
			<th><?= $this->Paginator->sort('user_id'); ?></th>
			<th><?= $this->Paginator->sort('created'); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($data as $one) {
			$audit = $one['Audit'];
			?>
			<tr>
				<td><?= $this->Html->link($audit['id'], array('action' => 'view', $audit['id'])); ?></td>
				<td><?= $audit['event']; ?></td>
				<td><?= $audit['model']; ?></td>
				<td><?= $audit['entity_id']; ?></td>
				<td><?= $this->Audit->user($one['User']); ?></td>
				<td nowrap="nowrap" title="<?= $audit['created']; ?>"><?= $this->Time->timeAgoInWords($audit['created']); ?></td>
				<td>
					<div class="btn-group"><button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-tasks"></i><span class="caret"></span></button>
						<ul class="dropdown-menu pull-right">
							<?= $this->Html->tag('li', $this->Html->link('View', array('action' => 'view', $audit['id']))); ?>
						</ul>
					</div>

				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>
<?php
echo $this->Form->end();
echo $this->element('pagination/pagination');
