<?php
namespace Services\Place;

use Quark\Quark;
use Quark\QuarkRole;
use Quark\QuarkJSONIOProcessor;

use Quark\Extensions\Mongo\Model;

use Quark\IQuarkGetService;
use Quark\IQuarkIOProcessor;
use Quark\IQuarkServiceWithCustomProcessor;
use Quark\IQuarkAuthorizableService;

/**
 * Class ListService
 * @package Services\Place
 */
class ListService implements IQuarkGetService, IQuarkServiceWithCustomProcessor, IQuarkAuthorizableService {
	/**
	 * @return array
	 */
	public function AuthorizationCriteria () {
		$criteria = array(
			QuarkRole::Authenticated()
		);

		return $criteria;
	}

	/**
	 * @return mixed
	 */
	public function AuthorizationFailed () {
		return Quark::Response(array(
			'status' => 403
		));
	}
	/**
	 * @return IQuarkIOProcessor
	 */
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	/**
	 * Response: {"status":"code","places":[]}
	 *
	 * @note Response is array of Place objects. It will be returned only if you are authorized
	 *
	 * Statuses:
	 * 200 - OK
	 * 403 - Permission denied
	 *
	 * @param mixed $request
	 * @return mixed
	 */
	public function Get ($request) {
		return Quark::Response(array(
			'status' => 200,
			'places' => Model::Find('Place')
		));
	}
} 