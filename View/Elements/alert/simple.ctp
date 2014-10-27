<?php
/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:51:06
 * Format: http://book.cakephp.org/2.0/en/views.html
 * 
 * @package AuditLog
 * @subpackage View.Element
 */
?>
<div class="alert <?= $class; ?>">
	<a class="close" data-dismiss="alert">×</a>
	<?= $this->Html->tag('strong', $title) . $message; ?>
</div>