<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:27:11
 * Format: http://book.cakephp.org/2.0/en/models.html
 */
App::uses('AppModel', 'Model');

/**
 * AuditLogAppModel Model
 * 
 * @package AuditLog
 * @subpackage Model
 */
class AuditLogAppModel extends AppModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'AuditLogAppModel';

	/**
	 * {@inheritdoc}
	 *
	 * @var arrayt
	 */
	public $actAs = array('Containable');

	/**
	 * {@inheritdoc}
	 *
	 * @var int
	 */
	public $recursive = -1;

}
