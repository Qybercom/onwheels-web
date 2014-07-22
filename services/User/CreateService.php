<?php
namespace Services\User;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

use Quark\IQuarkPostService;
use Quark\IQuarkServiceWithCustomProcessor;

use Quark\Extensions\Mongo\Model;

use Models\User;

class CreateService implements IQuarkPostService, IQuarkServiceWithCustomProcessor {
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	public function Post ($data) {
		$model = new Model(new User(), $data);

		if (!$model->Validate())
			return Quark::Response(array('status' => 400));

		$model->Save();

		return Quark::Response(array(
			'status' => 200,
			'user' => $model->Model()
		));
	}
}