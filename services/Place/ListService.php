<?php
/**
 * Created by PhpStorm.
 * User: alex025
 * Date: 23.07.14
 * Time: 9:22
 */

namespace Services\Place;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

use Quark\Extensions\Mongo\Model;

use Quark\IQuarkGetService;
use Quark\IQuarkIOProcessor;
use Quark\IQuarkServiceWithCustomProcessor;

/**
 * Class ListService
 * @package Services\Place
 */
class ListService implements IQuarkGetService, IQuarkServiceWithCustomProcessor {
	/**
	 * @return IQuarkIOProcessor
	 */
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	/**
	 * Response: []
	 *
	 * @note Response is array of Place objects
	 *
	 * @param mixed $request
	 * @return mixed
	 */
	public function Get ($request) {
		return Quark::Response(Model::Find('Place'));
	}
} 