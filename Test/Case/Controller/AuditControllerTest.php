<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 16:27:47
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
App::uses('User', 'Model');
App::uses('AppModel', 'Model');

if (!class_exists('User')) {

	/**
	 * User model
	 */
	class User extends AppModel {
		
	}

}

/**
 * AuditControllerTest
 * 
 * @package AuditLog
 * @subpackage Controller
 */
class AuditControllerTest extends ControllerTestCase {

	/**
	 * {@inheritdoc}
	 */
	public function setUp() {
		parent::setUp();
		Configure::write('Pagination.limit', 10);
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
	 * Test index action
	 * 
	 * @param array $query
	 * @param array $paginate
	 * @dataProvider indexProvider
	 */
	public function testIndex(array $query, array $paginate) {
		$Controller = $this->generate('AuditLog.Audit', array(
			'models' => array(
				'AuditLog.Audit' => array('find'),
				'User' => array('find'),
			),
			'methods' => array(
				'paginate',
				'render'
			)
		));
		if (!empty($query['list'])) {
			$Controller->expects($this->once())->method('render')->with('list');
		}
		$Controller->expects($this->once())->method('paginate')->with('Audit')->willReturn(array('Audit pagination data'));
		$Controller->User->expects($this->once())->method('find')->with('list', array('fields' => array('id', 'email')))->willReturn(array('users data'));

		$this->testAction('/audit_log/audit/index', array(
			'method' => 'GET',
			'data' => $query
		));

		$this->assertEqual($paginate, $Controller->paginate);
		$generatedQuery = $Controller->request->query;
		unset($generatedQuery['url']);
		$this->assertSame($query, $generatedQuery);
		$this->assertSame(array('Audit pagination data'), $Controller->viewVars['data']);
		$this->assertSame(array('users data'), $Controller->viewVars['users']);
		$this->assertSame(array('data'), $Controller->viewVars['_serialize']);
	}

	/**
	 * Data provider for testIndex
	 * 
	 * @return array
	 */
	public function indexProvider() {
		return array(
			//set #0
			array(
				//query
				array(),
				//paginate
				array(
					'Audit' => array(
						'limit' => 10,
						'fields' => array(
							'id',
							'event',
							'model',
							'created',
							'user_id',
							'entity_id'
						),
						'contain' => array(
							'User' => array(
								'fields' => array(
									'id',
									'email'
								)
							)
						),
						'conditions' => array(),
						'order' => array('created' => 'desc')
					)
				)
			),
			//set #1
			array(
				//query
				array(
					'id' => '544e33d1-debc-46f2-bc54-42aec0a85480',
					'created' => '01.01.2014 12:00:01 - 02.03.2014 01:00:10',
					'model' => 'Momo',
					'user_id' => array(1, 3, 6),
					'event' => array('EDIT', 'CREATE'),
					'entity_id' => '5'
				),
				//paginate
				array(
					'Audit' => array(
						'limit' => 10,
						'fields' => array(
							'id',
							'event',
							'model',
							'created',
							'user_id',
							'entity_id'
						),
						'contain' => array(
							'User' => array(
								'fields' => array(
									'id',
									'email'
								)
							)
						),
						'conditions' => array(
							'Audit.id' => '544e33d1-debc-46f2-bc54-42aec0a85480',
							'Audit.created BETWEEN ? AND ?' => array('2014-01-01 12:00:01', '2014-03-02 01:00:10'),
							'LOWER(Audit.model) LIKE' => '%momo%',
							'user_id' => array(1, 3, 6),
							'event' => array('EDIT', 'CREATE'),
							'entity_id' => '5'
						),
						'order' => array('created' => 'desc')
					)
				)
			),
			//set #2
			array(
				//query
				array(
					'list' => 1
				),
				//paginate
				array(
					'Audit' => array(
						'limit' => 10,
						'fields' => array(
							'id',
							'event',
							'model',
							'created',
							'user_id',
							'entity_id'
						),
						'contain' => array(
							'User' => array(
								'fields' => array(
									'id',
									'email'
								)
							)
						),
						'conditions' => array(),
						'order' => array('created' => 'desc')
					)
				)
			),
			//set #3
			array(
				//query
				array(
					'list' => 1,
					'count' => 3,
				),
				//paginate
				array(
					'Audit' => array(
						'limit' => 3,
						'fields' => array(
							'id',
							'event',
							'model',
							'created',
							'user_id',
							'entity_id'
						),
						'contain' => array(
							'User' => array(
								'fields' => array(
									'id',
									'email'
								)
							)
						),
						'conditions' => array(),
						'order' => array('created' => 'desc')
					)
				)
			),
			//set #4
			array(
				//query
				array(
					'created' => '2014-01-01 12:00:01',
				),
				//paginate
				array(
					'Audit' => array(
						'limit' => 10,
						'fields' => array(
							'id',
							'event',
							'model',
							'created',
							'user_id',
							'entity_id'
						),
						'contain' => array(
							'User' => array(
								'fields' => array(
									'id',
									'email'
								)
							)
						),
						'conditions' => array(
							'Audit.created' => '2014-01-01 12:00:01'
						),
						'order' => array('created' => 'desc')
					)
				)
			),
			//set #5
			array(
				//query
				array(
					'created' => '2014-01-01',
				),
				//paginate
				array(
					'Audit' => array(
						'limit' => 10,
						'fields' => array(
							'id',
							'event',
							'model',
							'created',
							'user_id',
							'entity_id'
						),
						'contain' => array(
							'User' => array(
								'fields' => array(
									'id',
									'email'
								)
							)
						),
						'conditions' => array(
							'Audit.created BETWEEN ? AND ?' => array('2014-01-01 00:00:00', '2014-01-01 23:59:59')
						),
						'order' => array('created' => 'desc')
					)
				)
			),
		);
	}

	/**
	 * Test view action
	 * 
	 * @param int $id
	 * @param string $exception
	 * @dataProvider viewProvider
	 */
	public function testView($id, $exception) {
		if ($exception) {
			$this->expectException($exception);
			$data = false;
		} else {
			$data = array('some data');
		}
		$Controller = $this->generate('AuditLog.Audit', array(
			'models' => array(
				'AuditLog.Audit' => array('find'),
			)
		));

		$Controller->Audit->expects($this->once())->method('find')
				->with('first', array(
					'contain' => array(
						'Delta',
						'User' => array(
							'fields' => array(
								'id',
								'email'
							)
						)
					),
					'conditions' => array(
						'Audit.id' => $id
					)
				))
				->willReturn($data);

		$this->testAction('/audit_log/audit/view/' . $id, array(
			'method' => 'GET'
		));

		$this->assertSame($data, $Controller->viewVars['data']);
	}

	/**
	 * Data provider for testView
	 * 
	 * @return array
	 */
	public function viewProvider() {
		return array(
			//set #0
			array(
				//id
				1,
				//exception
				'NotFoundException'
			),
			//set #1
			array(
				//id
				2,
				//exception
				null
			),
		);
	}

}
