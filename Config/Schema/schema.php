<?php

/**
 * Audit log schema file
 * 
 * generated on: 2012-02-21 14:38:35 : 1329856715 
 */

/**
 * AuditLogSchema
 * 
 * @package AuditLog
 * @subpackage Model
 */
class AuditLogSchema extends CakeSchema {

	/**
	 * Name
	 *
	 * @var string
	 * @access public
	 */
	public $name = 'AuditLog';

	/**
	 * Before callback
	 *
	 * @param array $event
	 * @return boolean
	 * @access public
	 */
	public function before($event = array()) {
		return true;
	}

	/**
	 * After callback
	 *
	 * @param array $event
	 * @return boolean
	 * @access public
	 */
	public function after($event = array()) {
		return true;
	}

	/**
	 * Schema for audit_deltas table
	 *
	 * @var array
	 * @access public
	 */
	public $audit_deltas = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'audit_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'property_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'old_value' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'new_value' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'audit_id' => array('column' => 'audit_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

	/**
	 * Schema for audits table
	 *
	 * @var array
	 * @access public
	 */
	public $audits = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'event' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'entity_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'json_object' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'biginteger', 'null' => true, 'default' => '0', 'unsigned' => true, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'id_UNIQUE' => array('column' => 'id', 'unique' => 1),
			'event' => array('column' => 'event', 'unique' => 0),
			'model' => array('column' => 'model', 'unique' => 0),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'created' => array('column' => 'created', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

}
