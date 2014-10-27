<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:27:44
 * Format: http://book.cakephp.org/2.0/en/models.html
 */
App::uses('AuditLogAppModel', 'AuditLog.Model');

/**
 * Audit Model
 * 
 * @package AuditLog
 * @subpackage Model
 */
class Audit extends AuditLogAppModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'Audit';

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $hasMany = array(
		'Delta' => array(
			'className' => 'AuditLog.AuditDelta'
		)
	);

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User'
	);

}
