<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 10:30:00
 */

/**
 * All AuditLog test suite
 * 
 * @package AllAuditTest
 * @subpackage Test
 */
class AllAuditLogTest extends PHPUnit_Framework_TestSuite {

	/**
	 * Suite define the tests for this suite
	 *
	 * @return void
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All AuditLog Tests');
		$path = App::pluginPath('AuditLog') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);
		return $suite;
	}

}
