<?php

/**
 * Auditable Behavior test file
 */
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');

/**
 * Article model
 *
 * @package       AuditLogTest
 * @subpackage    Model
 */
class Article extends CakeTestModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'Article';

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $actsAs = array(
		'AuditLog.Auditable' => array(
			'ignore' => array('ignored_field'),
		)
	);

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $belongsTo = array('Author');

	/**
	 * Search params
	 *
	 * @var array
	 */
	public $searchParams = array();
}

/**
 * Author model
 *
 * @package       AuditLogTest
 * @subpackage    Model
 */
class Author extends CakeTestModel {

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
	public $actsAs = array(
		'AuditLog.Auditable'
	);

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $hasMany = array('Article');

}

/**
 * Audit model
 *
 * @package       AuditLogTest
 * @subpackage    Model
 */
class Audit extends CakeTestModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $hasMany = array(
		'AuditDelta'
	);

}

/**
 * Audit Delta model
 *
 * @package       AuditLogTest
 * @subpackage    Model
 */
class AuditDelta extends CakeTestModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Audit'
	);

}

/**
 * AuditableBehavior test class.
 * 
 * @package       AuditLogTest
 * @subpackage    Model.Behavior
 */
class AuditableBehaviorTest extends CakeTestCase {

	/**
	 * Fixtures associated with this test case
	 *
	 * @var array
	 * @access public
	 */
	public $fixtures = array(
		'plugin.audit_log.article',
		'plugin.audit_log.author',
		'plugin.audit_log.audit',
		'plugin.audit_log.audit_delta',
	);

	/**
	 * Method executed before each test
	 *
	 * @access public
	 */
	public function setUp() {
		parent::setUp();
		$this->Article = ClassRegistry::init('Article');
		
		$config = (array)Configure::read('AuditLog');
		$config = Hash::mergeDiff($config, array(
					'models' => array(
						'Article' => array(
							'methods' => array(
								'all' => array(
									'activity' => array('find','save','delete')))),
						'Author' => array(
							'methods' => array(
								'all' => array(
									'activity' => array('save','delete'))))
		)));
		Configure::write('AuditLog', $config);
	}

	/**
	 * Method executed after each test
	 *
	 * @access public
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Article);
		ClassRegistry::flush();
	}

	/**
	 * Test the action of creating a new record.
	 */
	public function testCreate() {
		$newArticle = array(
			'Article' => array(
				'user_id' => 1,
				'author_id' => 1,
				'title' => 'First Test Article',
				'body' => 'First Test Article Body',
				'published' => 'N',
			),
		);

		$this->Article->save($newArticle);
		$audit = ClassRegistry::init('Audit')->find(
				'first', array(
			'recursive' => -1,
			'conditions' => array(
				'Audit.event' => 'CREATE',
				'Audit.model' => 'Article',
				'Audit.entity_id' => $this->Article->getLastInsertId()
			)
				)
		);

		$article = json_decode($audit['Audit']['json_object'], true);

		$deltas = ClassRegistry::init('AuditDelta')->find(
				'all', array(
			'recursive' => -1,
			'conditions' => array('AuditDelta.audit_id' => $audit['Audit']['id']),
				)
		);

		# Verify the audit record
		$this->assertEqual(1, $article['Article']['user_id']);
		$this->assertEqual('First Test Article', $article['Article']['title']);
		$this->assertEqual('N', $article['Article']['published']);

		#Verify that no delta record was created.
		$this->assertTrue(empty($deltas));
	}

	/**
	 * Test saving multiple records with Model::saveAll()
	 */
	public function testSaveAll() {
		# TEST A MODEL AND A SINGLE ASSOCIATED MODEL
		$data = array(
			'Article' => array(
				'user_id' => 1,
				'title' => 'Rob\'s Test Article',
				'body' => 'Rob\'s Test Article Body',
				'published' => 'Y',
			),
			'Author' => array(
				'first_name' => 'Rob',
				'last_name' => 'Wilkerson',
			),
		);

		$this->Article->saveAll($data);
		$articleAudit = ClassRegistry::init('Audit')->find(
				'first', array(
			'recursive' => -1,
			'conditions' => array(
				'Audit.event' => 'CREATE',
				'Audit.model' => 'Article',
				'Audit.entity_id' => $this->Article->getLastInsertId()
			)
				)
		);
		$article = json_decode($articleAudit['Audit']['json_object'], true);

		# Verify the audit record
		$this->assertEqual(1, $article['Article']['user_id']);
		$this->assertEqual('Rob\'s Test Article', $article['Article']['title']);
		$this->assertEqual('Y', $article['Article']['published']);

		# Verify that no delta record was created.
		$this->assertTrue(!isset($articleAudit['AuditDelta']));

		$authorAudit = ClassRegistry::init('Audit')->find(
				'first', array(
			'recursive' => -1,
			'conditions' => array(
				'Audit.event' => 'CREATE',
				'Audit.model' => 'Author',
				'Audit.entity_id' => $this->Article->Author->getLastInsertId()
			)
				)
		);
		$author = json_decode($authorAudit['Audit']['json_object'], true);

		# Verify the audit record
		$this->assertEqual($article['Article']['author_id'], $author['Author']['id']);
		$this->assertEqual('Rob', $author['Author']['first_name']);

		# Verify that no delta record was created.
		$this->assertTrue(!isset($authorAudit['AuditDelta']));

		# TEST MULTIPLE RECORDS OF ONE MODEL

		$data = array(
			array(
				'Article' => array(
					'user_id' => 1,
					'author_id' => 1,
					'title' => 'Multiple Save 1 Title',
					'body' => 'Multiple Save 1 Body',
					'published' => 'Y',
				),
			),
			array(
				'Article' => array(
					'user_id' => 2,
					'author_id' => 2,
					'title' => 'Multiple Save 2 Title',
					'body' => 'Multiple Save 2 Body',
					'published' => 'N',
					'ignored_field' => 1,
				)
			),
			array(
				'Article' => array(
					'user_id' => 3,
					'author_id' => 3,
					'title' => 'Multiple Save 3 Title',
					'body' => 'Multiple Save 3 Body',
					'published' => 'Y',
				)
			),
		);
		$this->Article->create();
		$this->Article->saveAll($data);

		# Retrieve the audits for the last 3 articles saved
		$audits = ClassRegistry::init('Audit')->find(
				'all', array(
			'conditions' => array(
				'Audit.event' => 'CREATE',
				'Audit.model' => 'Article',
			),
			'order' => array('Audit.entity_id DESC'),
			'limit' => 3
				)
		);

		$article1 = json_decode($audits[2]['Audit']['json_object'], true);
		$article2 = json_decode($audits[1]['Audit']['json_object'], true);
		$article3 = json_decode($audits[0]['Audit']['json_object'], true);

		# Verify the audit records
		$this->assertEqual(1, $article1['Article']['user_id']);
		$this->assertEqual('Multiple Save 1 Title', $article1['Article']['title']);
		$this->assertEqual('Y', $article1['Article']['published']);

		$this->assertEqual(2, $article2['Article']['user_id']);
		$this->assertEqual('Multiple Save 2 Title', $article2['Article']['title']);
		$this->assertEqual('N', $article2['Article']['published']);

		$this->assertEqual(3, $article3['Article']['user_id']);
		$this->assertEqual('Multiple Save 3 Title', $article3['Article']['title']);
		$this->assertEqual('Y', $article3['Article']['published']);

		# Verify that no delta records were created.
		$this->assertTrue(empty($audits[0]['AuditDelta']));
		$this->assertTrue(empty($audits[1]['AuditDelta']));
		$this->assertTrue(empty($audits[2]['AuditDelta']));
	}

	/**
	 * Test editing an existing record.
	 */
	public function testEdit() {
		$this->Audit = ClassRegistry::init('Audit');
		$this->AuditDelta = ClassRegistry::init('AuditDelta');

		$newArticle = array(
			'Article' => array(
				'user_id' => 1,
				'author_id' => 1,
				'title' => 'First Test Article',
				'body' => 'First Test Article Body',
				'ignored_field' => 1,
				'published' => 'N',
			),
		);

		# TEST SAVE WITH SINGLE PROPERTY UPDATE

		$this->Article->save($newArticle);
		$this->Article->saveField('title', 'First Test Article (Edited)');

		$auditRecords = $this->Audit->find(
				'all', array(
			'recursive' => 0,
			'conditions' => array(
				'Audit.model' => 'Article',
				'Audit.entity_id' => $this->Article->getLastInsertId()
			)
				)
		);
		$deltaRecords = $this->AuditDelta->find(
				'all', array(
			'recursive' => -1,
			'conditions' => array('AuditDelta.audit_id' => Set::extract('/Audit/id', $auditRecords)),
				)
		);

		$createAudit = Set::extract('/Audit[event=CREATE]', $auditRecords);
		$updateAudit = Set::extract('/Audit[event=EDIT]', $auditRecords);

		# There should be 1 CREATE and 1 EDIT record
		$this->assertEqual(2, count($auditRecords));

		# There should be one audit record for each event.
		$this->assertEqual(1, count($createAudit));
		$this->assertEqual(1, count($updateAudit));

		# Only one property was changed
		$this->assertEqual(1, count($deltaRecords));

		$delta = array_shift($deltaRecords);
		$this->assertEqual('First Test Article', $delta['AuditDelta']['old_value']);
		$this->assertEqual('First Test Article (Edited)', $delta['AuditDelta']['new_value']);

		# TEST UPDATE OF MULTIPLE PROPERTIES
		# Pause to simulate a gap between edits
		# This also allows us to retrieve the last edit for the next set
		# of tests.
		$this->Article->create(); # Clear the article id so we get  a new record.
		$newArticle = array(
			'Article' => array(
				'user_id' => 1,
				'author_id' => 1,
				'title' => 'Second Test Article',
				'body' => 'Second Test Article Body',
				'ignored_field' => 1,
				'published' => 'N',
			),
		);
		$this->Article->save($newArticle);

		$updatedArticle = array(
			'Article' => array(
				'user_id' => 1,
				'author_id' => 1,
				'title' => 'Second Test Article (Newly Edited)',
				'body' => 'Second Test Article Body (Also Edited)',
				'ignored_field' => 0,
				'published' => 'Y',
			),
		);
		$this->Article->save($updatedArticle);

		$lastAudit = $this->Audit->find(
				'first', array(
			'contain' => array('AuditDelta'),
			'conditions' => array(
				'Audit.event' => 'EDIT',
				'Audit.model' => 'Article',
				'Audit.entity_id' => $this->Article->id
			),
			'order' => 'Audit.created DESC',
				)
		);

		# There are 4 changes, but one to an ignored field
		$this->assertEqual(3, count($lastAudit['AuditDelta']));
		$result = Set::extract('/AuditDelta[property_name=title]/old_value', $lastAudit);
		$this->assertEqual('Second Test Article', array_shift($result));

		$result = Set::extract('/AuditDelta[property_name=title]/new_value', $lastAudit);
		$this->assertEqual('Second Test Article (Newly Edited)', array_shift($result));

		$result = Set::extract('/AuditDelta[property_name=body]/old_value', $lastAudit);
		$this->assertEqual('Second Test Article Body', array_shift($result));

		$result = Set::extract('/AuditDelta[property_name=body]/new_value', $lastAudit);
		$this->assertEqual('Second Test Article Body (Also Edited)', array_shift($result));

		$result = Set::extract('/AuditDelta[property_name=published]/old_value', $lastAudit);
		$this->assertEqual('N', array_shift($result));

		$result = Set::extract('/AuditDelta[property_name=published]/new_value', $lastAudit);
		$this->assertEqual('Y', array_shift($result));

		# No delta should be reported against the ignored field.
		$this->assertIdentical(array(), Set::extract('/AuditDelta[property_name=ignored_field]', $lastAudit));
	}

	/**
	 * Test ignored field
	 */
	public function testIgnoredField() {
		$this->Audit = ClassRegistry::init('Audit');
		$this->AuditDelta = ClassRegistry::init('AuditDelta');

		$newArticle = array(
			'Article' => array(
				'user_id' => 1,
				'author_id' => 1,
				'title' => 'First Test Article',
				'body' => 'First Test Article Body',
				'ignored_field' => 1,
				'published' => 'N',
			),
		);

		# TEST NO AUDIT RECORD IF ONLY CHANGE IS IGNORED FIELD

		$this->Article->save($newArticle);
		$this->Article->saveField('ignored_field', '5');

		$lastAudit = $this->Audit->find(
				'count', array(
			'contain' => array('AuditDelta'),
			'conditions' => array(
				'Audit.event' => 'EDIT',
				'Audit.model' => 'Article',
				'Audit.entity_id' => $this->Article->id
			),
			'order' => 'Audit.created DESC',
				)
		);

		$this->assertEqual(0, $lastAudit);
	}

	/**
	 * Test delete
	 */
	public function testDelete() {
		$this->Audit = ClassRegistry::init('Audit');
		$this->AuditDelta = ClassRegistry::init('AuditDelta');
		$article = $this->Article->find(
				'first', array(
			'contain' => false,
			'order' => array('rand()'),
				)
		);

		$id = $article['Article']['id'];

		$this->Article->delete($id);

		$lastAudit = $this->Audit->find(
				'all', array(
			//'contain'    => array( 'AuditDelta' ), <-- What does this solve?
			'conditions' => array(
				'Audit.event' => 'DELETE',
				'Audit.model' => 'Article',
				'Audit.entity_id' => $id,
			),
			'order' => 'Audit.created DESC',
				)
		);

		$this->assertEqual(1, count($lastAudit));
	}
	
	/**
	 * Test the action of find a new record.
	 */
	public function testFind() {
		$newArticle = array(
			'Article' => array(
				'user_id' => 1,
				'author_id' => 1,
				'title' => 'First Test Article',
				'body' => 'First Test Article Body',
				'published' => 'N',
			),
		);
		$this->Article->save($newArticle);
		
		$params = array(
			'fields' => array('Article.title', 'Article.body'),
			'conditions' => array('Article.user_id' => 1)
		);
		$this->Article->searchParams = $params;
		$article = $this->Article->find('first', $params);

		$audit = ClassRegistry::init('Audit')->find(
				'first', array(
			'recursive' => -1,
			'conditions' => array(
				'Audit.event' => 'FIND',
				'Audit.model' => 'Article',
				'Audit.entity_id' => $this->Article->getLastInsertId()
			)
				)
		);

		$json_object = json_decode($audit['Audit']['json_object'], true);

		# Verify that real request and answer data the same with saved data
		$this->assertEqual($params, Hash::extract($json_object, 'Request.searchParams'));
		$this->assertEqual($article, Hash::extract($json_object, 'Answer.0'));
	}
}
