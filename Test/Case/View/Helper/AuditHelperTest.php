<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 16:02:16
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
App::uses('View', 'View');
App::uses('AuditHelper', 'AuditLog.View/Helper');

/**
 * AuditHelperTest
 * 
 * @package AuditLogTest
 * @subpackage View.Helper
 */
class AuditHelperTest extends CakeTestCase {

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
		Configure::write('AuditLog', array(
			'User' => array(
				'name' => 'email',
				'url' => array(
					'plugin' => false,
					'controller' => 'users',
					'action' => 'view',
					'___id___'
				)
			)
		));
	}

	/**
	 * Test user formatter
	 * 
	 * @param mixed $user
	 * @param string $output
	 * @dataProvider userProvider
	 */
	public function testUser($user, $output) {
		$Helper = new AuditHelper(new View);
		$this->assertStringMatchesFormat($output, $Helper->user($user));
	}

	/**
	 * Data provider for testUser
	 * 
	 * @return array
	 */
	public function userProvider() {
		return array(
			//set #0
			array(
				//user
				array(),
				//output
				__('Anonymous user')
			),
			//set #1
			array(
				//user
				null,
				//output
				__('Anonymous user')
			),
			//set #2
			array(
				//user
				array(
					'id' => null
				),
				//output
				__('Anonymous user')
			),
			//set #3
			array(
				//user
				array(
					'id' => 123,
					'email' => 'example@com.com'
				),
				//output
				'<a href="%s/123%S">example@com.com</a>'
			),
		);
	}

	/**
	 * Test list block
	 * 
	 * @param array $conditions
	 * @param bool $ajax
	 * @param string $output
	 * 
	 * @dataProvider listBlockProvider
	 */
	public function testListBlock($conditions, $ajax, $output) {
		$Helper = $this->getMock('AuditHelper', array('requestAction'), array(new View));
		$Helper->expects($this->exactly((int)!$ajax))->method('requestAction')->willReturn('audit data');
		$this->assertStringMatchesFormat($output, $Helper->listBlock($conditions, $ajax));
	}

	/**
	 * Data provider for testListBlock
	 * 
	 * @return array
	 */
	public function listBlockProvider() {
		return array(
			//set #0
			array(
				//conditions
				array(),
				//ajax
				true,
				//output
				'<div id="%s" class="audit-list"><script type="text/javascript">
//<![CDATA[
$(document).ready(function() { $("#%s").load("/audit_log/audit?list=1"); });
//]]>
</script></div>'
			),
			//set #1
			array(
				//conditions
				array('count' => 3),
				//ajax
				true,
				//output
				'<div id="%s" class="audit-list"><script type="text/javascript">
//<![CDATA[
$(document).ready(function() { $("#%s").load("/audit_log/audit?list=1&count=3"); });
//]]>
</script></div>'
			),
			//set #2
			array(
				//conditions
				array('count' => 3),
				//ajax
				false,
				//output
				'<div id="%s" class="audit-list">audit data</div>'
			),
		);
	}

}
