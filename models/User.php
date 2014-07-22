<?php
namespace Models;

use Quark\QuarkField;
use Quark\Extensions\Mongo\IMongoModel;

use Quark\IQuarkAuthorizableModel;

/**
 * Class User
 *
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $locale
 *
 * @package Models
 */
class User implements IMongoModel, IQuarkAuthorizableModel {
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
			'id' => '',
			'first_name' => '',
			'last_name' => '',
			'gender' => '',
			'locale' => ''
		);
	}

	/**
	 * @return array
	 */
	public function Rules () {
		return array(
			QuarkField::Type($this->id, 'string'),
			QuarkField::Type($this->first_name, 'string'),
			QuarkField::Type($this->last_name, 'string'),
			QuarkField::Type($this->gender, 'string'),
			QuarkField::Type($this->locale, 'string')
		);
	}

	/**
	 * @return array|mixed
	 */
	public function LoginCriteria () {
		return array(
			'id' => $this->id
		);
	}
}