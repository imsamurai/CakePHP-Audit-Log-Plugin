<?php
/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 13:35:54
 * Format: http://book.cakephp.org/2.0/en/views.html
 */
/* @var $this View */
?>
<h1><?= __('View audit'); ?></h1>
<table class="table table-bordered table-striped">
	<tr>
		<td><strong><?= __('Id'); ?></strong></td>
		<td><?= $data['Audit']['id']; ?></td>
	</tr>
	<tr>
		<td><strong><?= __('Event'); ?></strong></td>
		<td><?= $data['Audit']['event']; ?></td>
	</tr>
	<tr>
		<td><strong><?= __('Model'); ?></strong></td>
		<td><?= $data['Audit']['model']; ?></td>
	</tr>
	<tr>
		<td><strong><?= __('User'); ?></strong></td>
		<td><?= $this->Audit->user($data['User']); ?></td>
	</tr>
	<tr>
		<td><strong><?= __('Created'); ?></strong></td>
		<td><?= $this->Time->timeAgoInWords($data['Audit']['created']) . ' (' . $data['Audit']['created'] . ')'; ?></td>
	</tr>
</table>
<h2><?= __('Audit delta'); ?></h2>
<table class="table table-bordered table-striped">
	<tr>
		<th><?= __('Property name'); ?></th>
		<th><?= __('Old value'); ?></th>
		<th><?= __('New value'); ?></th>
	</tr>
	<?php
	foreach ($data['Delta'] as $delta) {
		?>

		<tr>
			<td><?= $delta['property_name']; ?></td>
			<td style="white-space: pre"><?= $delta['old_value']; ?></td>
			<td style="white-space: pre"><?= $delta['new_value']; ?></td>
		</tr>
		<?php
	}
	?>
</table>
<h2><?= __('Audit object'); ?></h2>
<pre><?= $data['Audit']['json_object']; ?></pre>