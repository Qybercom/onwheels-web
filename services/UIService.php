<?php
namespace Services;

use Quark\IQuarkGetService;
use Quark\IQuarkIOProcessor;
use Quark\IQuarkPostService;
use Quark\IQuarkServiceWithCustomProcessor;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

class UIService implements IQuarkGetService, IQuarkPostService, IQuarkServiceWithCustomProcessor {
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
		return Quark::Response(Quark::View('UI'));
	}

	/**
	 * @param mixed $request
	 * @return mixed
	 */
	public function Post ($request) {
		return Quark::Response(array(
			'status' => 200,
			'data' => $request
		));
	}
}