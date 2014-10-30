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
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<tr>
			<th colspan="2"><?= __('Audit log'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($data as $one) {
			$audit = $one['Audit'];
			?>
			<tr>
				<td>
					<?= __('User %s %s model %s with key "%s" %s', 
							$this->Audit->user($one['User']), 
							$audit['event'], 
							$audit['model'], 
							$audit['entity_id'], 
							
							$this->Html->tag('span', '—', array('style' => 'color: #aaa')) . ' ' .
							$this->Html->tag('span', $this->Time->timeAgoInWords($audit['created']), array(
								'style' => 'color: #aaa',
								'title' => $audit['created']
								))
							); ?>
				</td>
				<td><?= $this->Html->link(__('view'), array('action' => 'view', $audit['id'])); ?></td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td colspan="2">
					<center>
						<?php
						$query = $this->request->data('Audit');
						unset($query['list'], $query['count']);
						echo $this->Html->link(__('view more'), array('action' => 'index', '?' => $query));
						?>
					</center>
				</td>
			</tr>
</tbody>
</table>
