<?php
namespace Models;

use Quark\QuarkField;

use Quark\Extensions\Mongo\Model;

use Quark\Extensions\Mongo\IMongoModel;
use Quark\Extensions\Mongo\IMongoModelWithAfterFind;
use Quark\Extensions\Mongo\IMongoModelWithBeforeSave;

/**
 * Class Place
 *
 * @property string $author
 * @property string $date
 * @property object $position
 * @property array $navpoints
 * @property array $participants
 * @property string $name
 * @property string $description
 *
 * @package Models
 */
class Place implements IMongoModel, IMongoModelWithAfterFind, IMongoModelWithBeforeSave {
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
			'type' => 'race',
			'author' => '',
			'date' => '',
			'navpoints' => array(),
			'participants' => array(),
			'position' => null,
			'name' => null,
			'description' => null
		);
	}

	/**
	 * @return array
	 */
	public function Rules () {
		return array(
			QuarkField::In($this->type, array('race', 'rnr', 'source', 'studio', 'store')),
			QuarkField::Type($this->name, 'string', true),
			QuarkField::Type($this->description, 'string', true),
			QuarkField::DateTime($this->date, true),
			QuarkField::MinLengthInclusive($this->navpoints, 2, true),
			QuarkField::MinLengthInclusive($this->participants, 1, true)
		);
	}

	/**
	 * @param $item
	 * @return mixed
	 */
	public function AfterFind ($item) {
		$item['author'] = Model::GetById('User', $item['author']);

		return $item;
	}

	/**
	 * @return bool|null
	 */
	public function BeforeSave () {
		if (sizeof($this->navpoints) != 0)
			$this->position = $this->navpoints[1];
	}
}