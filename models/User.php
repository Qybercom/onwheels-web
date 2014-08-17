<?php
namespace Models;

use Quark\Quark;
use Quark\QuarkField;

use Quark\IQuarkAuthorizableModel;

use Quark\Extensions\Mongo\Model;
use Quark\Extensions\Mongo\IMongoModel;
use Quark\Extensions\Mongo\IMongoModelWithAfterFind;
use Quark\Extensions\Mongo\IMongoModelWithBeforeSave;

/**
 * Class User
 *
 * @property string $_id
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $locale
 * @property array $role
 *
 * @package Models
 */
class User implements IMongoModel, IQuarkAuthorizableModel, IMongoModelWithBeforeSave, IMongoModelWithAfterFind {
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
			'locale' => '',
			'role' => 'user',
			'distance' => 0.0,
			'achievements' => array(),
			'achievementCount' => 0
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
			QuarkField::Type($this->locale, 'string'),
			QuarkField::Type($this->role, 'string')
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

	/**
	 * @return array|mixed
	 */
	public function SystemRole () {
		return $this->role;
	}

	/**
	 * @return IQuarkAuthorizableModel
	 */
	public function RenewSession () {
		return Model::GetById('User', $this->_id);
	}

	/**
	 * @return bool|null
	 */
	public function BeforeSave () {
		$this->role = isset($this->role) ? $this->role : 'user';
	}

	/**
	 * @param $item
	 * @return mixed
	 */
	public function AfterFind ($item) {
		$item->distance = 0.0;

		$races = Model::Find(
			'Place',
			array(
				'type' => 'race',
				'participants' => array(
					'$in' => array($item->_id->{'$id'})
				)
			),
			array(
				'afterFind' => false
			)
		);

		foreach ($races as $i => $race)
			$item->distance += $race->length;

		$item->distance = round($item->distance, 2);

		/**
		 * @TODO move to db side
		 */
		$item->achievements = array('Test achievement');
		$item->achievementCount = sizeof($item->achievements);

		return $item;
	}
}