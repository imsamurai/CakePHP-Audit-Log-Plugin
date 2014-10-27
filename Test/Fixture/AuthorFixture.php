<?php

/**
 * Author Fixture file
 */

/**
 * AuthorFixture
 * 
 * @package AuditLogTest
 * @subpackage Fixture
 */
class AuthorFixture extends CakeTestFixture {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'Author';

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'first_name' => array('type' => 'string', 'null' => false),
		'last_name' => array('type' => 'string', 'null' => false),
		'created' => 'datetime',
		'updated' => 'datetime'
	);

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $records = array();

}
