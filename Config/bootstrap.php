<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 27.10.2014
 * Time: 12:51:06
 */
Configure::write('Pagination.pages', Configure::read('Pagination.pages') ? Configure::read('Pagination.pages') : 10);
$config = (array)Configure::read('AuditLog');
$config = Hash::mergeDiff($config, array(
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
Configure::write('AuditLog', $config);
