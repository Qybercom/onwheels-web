<?php
namespace Services\User;

use Quark\IQuarkGetService;
use Quark\IQuarkIOProcessor;
use Quark\IQuarkServiceWithCustomProcessor;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

/**
 * Class LogoutService
 * @package Services\User
 */
class LogoutService implements IQuarkGetService, IQuarkServiceWithCustomProcessor {
	/**
	 * @return IQuarkIOProcessor
	 */
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	/**
	 * @param mixed $request
	 * @return mixed
	 */
	public function Get ($request) {
		Quark::Logout();

		return Quark::Response(array(
			'status' => 200
		));
	}
}