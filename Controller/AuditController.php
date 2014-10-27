<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:36:50
 * Format: http://book.cakephp.org/2.0/en/controllers.html
 */
App::uses('AuditLogAppController', 'AuditLog.Controller');

/**
 * AuditController
 * 
 * @property Audit $Audit Audit model
 * @property User $User User model
 * 
 * @package AuditLog
 * @subpackage Controller
 */
class AuditController extends AuditLogAppController {

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $uses = array(
		'User', 'AuditLog.Audit'
	);

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $helpers = array(
		'AuditLog.Audit', 'Time'
	);

	/**
	 * List of audit records
	 */
	public function index() {
		$this->request->data('Audit', $this->request->query);
		$list = $this->request->data('Audit.list');
		$count = $this->request->data('Audit.count');
		$this->paginate = array(
			'Audit' => array(
				'limit' => ($list && $count) ? $count : Configure::read('Pagination.limit'),
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
							Configure::read('AuditLog.User.name')
						)
					)
				),
				'conditions' => $this->_paginationFilter(),
				'order' => array('created' => 'desc'),
			)
		);
		$this->set(array(
			'data' => $this->paginate("Audit"),
			'users' => $this->User->find('list', array(
				'fields' => array('id', Configure::read('AuditLog.User.name'))
			)),
			'_serialize' => array('data')
		));
		if ($list) {
			$this->render('list');
		}
	}

	/**
	 * View audit details
	 * 
	 * @param string $id
	 * @throws NotFoundException
	 */
	public function view($id) {
		$data = $this->Audit->find('first', array(
			'contain' => array(
				'Delta',
				'User' => array(
					'fields' => array(
						'id',
						Configure::read('AuditLog.User.name')
					)
				)
			),
			'conditions' => array(
				$this->Audit->alias . '.id' => $id
			)
		));
		if (!$data) {
			throw new NotFoundException(__("Audit #%s does not exists!", $id));
		}
		$this->set('data', $data);
	}

	/**
	 * Builds pagination conditions from search form
	 * 
	 * @return array
	 */
	protected function _paginationFilter() {
		$conditions = array_filter($this->request->query, function($var) {
			return $var !== '';
		});
		unset($conditions['url']);
		foreach (array('created') as $dateRangeField) {
			if (empty($conditions[$dateRangeField])) {
				continue;
			}
			if (preg_match('/^(?P<start>.*)\s(-|to)\s(?P<end>.*)$/is', $conditions[$dateRangeField], $range)) {
				$conditions[$this->Audit->alias . '.' . $dateRangeField . ' BETWEEN ? AND ?'] = array(
					(new DateTime($range['start']))->format('Y-m-d H:i:s'),
					(new DateTime($range['end']))->format('Y-m-d H:i:s')
				);
			}
			unset($conditions[$dateRangeField]);
		}

		if (!empty($conditions['model'])) {
			$conditions['LOWER(' . $this->Audit->alias . '.model) LIKE'] = "%" . mb_strtolower($conditions['model']) . "%";
		}
		unset($conditions['model']);

		if (!empty($conditions['id'])) {
			$conditions[$this->Audit->alias . '.id'] = $conditions['id'];
		}
		unset($conditions['id']);
		unset($conditions['list']);
		unset($conditions['count']);

		return $conditions;
	}

}
