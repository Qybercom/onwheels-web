<?php
namespace Models;

use Quark\Quark;
use Quark\QuarkField;

use Quark\Extensions\Mongo\Model;

use Quark\Extensions\Mongo\IMongoModel;
use Quark\Extensions\Mongo\IMongoModelWithAfterFind;
use Quark\Extensions\Mongo\IMongoModelWithBeforeSave;

/**
 * Class Place
 *
 * @property string $type
 * @property string $author
 * @property string $date
 * @property object $position
 * @property array $navpoints
 * @property array $participants
 * @property string $name
 * @property string $description
 * @property float $length
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
			'date' => null,
			'navpoints' => null,
			'participants' => null,
			'position' => null,
			'name' => '',
			'description' => null,
			'length' => 0.0
		);
	}

	/**
	 * @return array
	 */
	public function Rules () {
		Quark::Log(print_r($this, true));

		return array(
			QuarkField::In($this->type, array('race', 'rnr', 'source', 'studio', 'store')),
			QuarkField::Type($this->name, 'string'),
			QuarkField::Type($this->description, 'string', true),
			QuarkField::MinLengthInclusive($this->navpoints, 2, $this->type != 'race'),
			QuarkField::DateTime($this->date, $this->type != 'race'),
			QuarkField::MinLengthInclusive($this->navpoints, 2, true),
			QuarkField::MinLengthInclusive($this->participants, 1, true),
			QuarkField::Type($this->length, 'float')
		) + ($this->position != null ? array(
			QuarkField::Rules(array(
				QuarkField::Type($this->position->lat, 'string', null),
				QuarkField::Type($this->position->lng, 'string', null)
			))
		) : array());
	}

	/**
	 * @param $item
	 * @return mixed
	 */
	public function AfterFind ($item) {
		$item->author = Model::GetById('User', $item->author);

		return $item;
	}

	private static function _length ($navpoints = [], $type = Quark::KEY_TYPE_OBJECT) {
		if (!is_array($navpoints) || sizeof($navpoints) == 0) return 0.0;

		$length = 0.0;

		$prev = $navpoints[0];

		foreach ($navpoints as $i => $point) {
			$length += self::DistanceHaversine(
				Quark::valueForKey($prev, 'lat', $type),
				Quark::valueForKey($prev, 'lng', $type),
				Quark::valueForKey($point, 'lat', $type),
				Quark::valueForKey($point, 'lng', $type)
			);

			$prev = $point;
		}

		return $length;
	}

	/**
	 * @return bool|null
	 */
	public function BeforeSave () {
		if (sizeof($this->navpoints) == 0) return true;

		$this->position = $this->navpoints[0];
		$this->length = self::_length($this->navpoints);

		return true;
	}

	const EARTH_LENGTH = 40075;
	const EARTH_GRADS = 360;
	//const

	/**
	 * @param $lat1
	 * @param $lng1
	 * @param $lat2
	 * @param $lng2
	 * @return float
	 */
	public static function DistancePifagor ($lat1, $lng1, $lat2, $lng2) {
		$lat = (float)((float)$lat1 - (float)$lat2);
		$lng = (float)((float)$lng1 - (float)$lng2);

		$distance = (float)sqrt((float)((float)pow($lat, 2) + (float)pow($lng, 2)));

		return ((float)self::EARTH_LENGTH / (float)self::EARTH_GRADS) * $distance;
	}

	/**
	 * @param $lat1
	 * @param $lng1
	 * @param $lat2
	 * @param $lng2
	 * @return float
	 *
	 * Code snippet from http://www.nmcmahon.co.uk/getting-the-distance-between-two-locations-using-google-maps-api-and-php/
	 */
	public static function DistanceHaversine ($lat1, $lng1, $lat2, $lng2) {
		$theta = $lng1 - $lng2;
		$distance = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);

		$distance = $distance * 60 * 1.1515;

		return round($distance, 2) * 1.609344;
	}
}