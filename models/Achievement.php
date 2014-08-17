<?php
namespace Models;

use Quark\Extensions\Mongo\IMongoModel;
use Quark\QuarkField;

/**
 * Class Achievement
 *
 * @property string $name
 * @property string $description
 *
 * @package Models
 */
class Achievement implements IMongoModel {
	/**
	 * @return string
	 */
	public static function Storage () {
		return 'main';
	}

	/**
	 * @return mixed
	 */
	public function Fields () {
		return array(
			'name' => '',
			'description' => ''
		);
	}

	/**
	 * @return mixed
	 */
	public function Rules () {
		return array(
			QuarkField::Type($this->name, 'string'),
			QuarkField::Type($this->description, 'string')
		);
	}
}