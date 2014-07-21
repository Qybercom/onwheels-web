<?php
namespace Services;

use Quark\Quark;
use Quark\IQuarkGetService;

use Quark\Extensions\Mongo\Model;

use Models\Place;

class IndexService implements IQuarkGetService {
	public function Get ($data) {
		return Quark::View(array(
			'places' => Model::Find('Place')
		));
	}
}