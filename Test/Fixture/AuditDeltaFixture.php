<?php

/**
 * AuditDelta Fixture file
 */

/**
 * AuditDeltaFixture
 * 
 * @package AuditLogTest
 * @subpackage Fixture
 */
class AuditDeltaFixture extends CakeTestFixture {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'AuditDelta';

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'string', 'length' => 36, 'null' => false),
		'audit_id' => array('type' => 'string', 'length' => 36, 'null' => false),
		'property_name' => array('type' => 'string', 'length' => 255, 'null' => false),
		'old_value' => array('type' => 'string', 'length' => 255),
		'new_value' => array('type' => 'string', 'length' => 255),
	);

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $records = array();

}
