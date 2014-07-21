<?php
namespace Models;

use Quark\QuarkField;
use Quark\Extensions\Mongo\IMongoModel;

/**
 * Class Place
 *
 * @property string $author
 * @property string $date
 * @property array $navpoints
 * @property array $participants
 *
 * @package Models
 */
class Place implements IMongoModel {
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
			'author' => '',
			'date' => '',
			'navpoints' => array(),
			'participants' => array()
		);
	}

	/**
	 * @return array
	 */
	public function Rules () {
		return array(
			QuarkField::Type($this->author, 'string'),
			QuarkField::DateTime($this->date),
			QuarkField::MinLengthInclusive($this->navpoints, 1),
			QuarkField::MinLengthInclusive($this->participants, 1)
		);
	}
} 