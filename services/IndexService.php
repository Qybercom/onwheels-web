<?php
namespace Services;

use Quark\Quark;
use Quark\IQuarkGetService;

class IndexService implements IQuarkGetService {
	public function Get () {
		echo Quark::View(array(
			'places' => array()
		));
	}
}