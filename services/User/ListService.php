<?php
namespace Services\User;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

use Quark\IQuarkGetService;
use Quark\IQuarkServiceWithCustomProcessor;

use Quark\Extensions\Mongo\Model;

use Models\User;

class ListService implements IQuarkGetService, IQuarkServiceWithCustomProcessor {
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	public function Get ($data) {
		echo Quark::Response(Model::Find('User'));
	}
}