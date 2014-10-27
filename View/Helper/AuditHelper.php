<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 13:42:55
 * Format: http://book.cakephp.org/2.0/en/views/helpers.html
 */
App::uses('AppHelper', 'View/Helper');

/**
 * AuditHelper
 * 
 * @property HtmlHelper $Html Html helper
 * 
 * @package AuditLog
 * @subpackage View.Helper
 */
class AuditHelper extends AppHelper {

	/**
	 * {@inheritdoc}
	 *
	 * @var array
	 */
	public $helpers = array(
		'Html'
	);

	/**
	 * Formats user
	 * 
	 * @param array $user
	 * @return string
	 */
	public function user(array $user = null) {
		if (!empty($user['id'])) {
			$link = str_replace('___id___', $user['id'], Router::url(Configure::read('AuditLog.User.url')));
			return $this->Html->link($user[Configure::read('AuditLog.User.name')], $link);
		} else {
			return __('Anonymous user');
		}
	}

	/**
	 * Inserts audit list
	 * 
	 * @param array $conditions
	 * @param bool $ajax
	 */
	public function listBlock(array $conditions = array(), $ajax = true) {
		$id = String::uuid();
		$url = Router::url(array(
					'plugin' => 'audit_log',
					'controller' => 'audit',
					'action' => 'index',
					'?' => array('list' => true) + $conditions
		));
		if ($ajax) {
			$script = '$(document).ready(function() { $("#%s").load("%s"); });';
			$content = (string)$this->Html->scriptBlock(sprintf($script, $id, $url));
		} else {
			$content = $this->requestAction($url);
		}
		return $this->Html->div('audit-list', $content, array('id' => $id));
	}

}
