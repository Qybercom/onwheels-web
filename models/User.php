<?php
namespace Models;

use Quark\QuarkField;
use Quark\Extensions\Mongo\IMongoModel;

/**
 * Class User
 *
 * @property string $login
 * @property string $password
 *
 * @package Models
 */
class User implements IMongoModel {
	/**
	 * @return string
	 */
	public static function Storage () {
		return 'main';
	}

	/**
	 * @return array|mixed
	 */
	public function Fields () {
		return array(
			'login' => '',
			'password' => ''
		);
	}

	/**
	 * @return array
	 */
	public function Rules () {
		return array(
			QuarkField::Type($this->login, 'string'),
			QuarkField::Type($this->password, 'string')
		);
	}
}