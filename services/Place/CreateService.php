<?php
namespace Services\Place;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

use Quark\IQuarkPostService;
use Quark\IQuarkCustomProcessorService;

use Quark\Extensions\Mongo\Model;

use Models\Place;

class CreateService implements IQuarkPostService, IQuarkCustomProcessorService {
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	public function Post ($data) {
		$model = new Model(new Place(), $data);

		if (!$model->Validate())
			return Quark::Response(array('status' => 400));

		$model->Save();

		return Quark::Response(array(
			'status' => 200,
			'user' => $model->Model()
		));
	}
}