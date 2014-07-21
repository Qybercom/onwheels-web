<?php
namespace Services\User;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

use Quark\IQuarkGetService;
use Quark\IQuarkCustomProcessorService;

use Quark\Extensions\Mongo\Model;

use Models\User;

class ListService implements IQuarkGetService, IQuarkCustomProcessorService {
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	public function Get ($data) {
		echo Quark::Response(Model::Find('User'));
	}
}