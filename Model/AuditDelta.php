<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:27:44
 * Format: http://book.cakephp.org/2.0/en/models.html
 */
App::uses('AuditLogAppModel', 'AuditLog.Model');

/**
 * AuditDelta Model
 * 
 * @package AuditLog
 * @subpackage Model
 */
class AuditDelta extends AuditLogAppModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'AuditDelta';

}
