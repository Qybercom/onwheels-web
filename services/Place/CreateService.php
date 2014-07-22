<?php
namespace Services\Place;

use Quark\IQuarkServiceWithRequestPreprocessor;
use Quark\Quark;
use Quark\QuarkRole;
use Quark\QuarkJSONIOProcessor;

use Quark\IQuarkPostService;
use Quark\IQuarkServiceWithCustomProcessor;
use Quark\IQuarkAuthorizableService;

use Quark\Extensions\Mongo\Model;

use Models\Place;

class CreateService implements
	IQuarkPostService,
	IQuarkServiceWithCustomProcessor,
	IQuarkServiceWithRequestPreprocessor,
	IQuarkAuthorizableService {
	private $_data = array();

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function Request ($data) {
		$this->_data = $data;
	}

	/**
	 * @return \Quark\IQuarkIOProcessor
	 */
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	/**
	 * @return array
	 */
	public function AuthorizationCriteria () {
		$criteria = array(
			QuarkRole::Authenticated()
		);

		if (isset($this->_data['type']))
			$criteria[] = Quark::Access('admin');

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
	 * @param mixed $data
	 * @return mixed
	 */
	public function Post ($data) {
		$author = Quark::User()->_id;

		$model = new Model(new Place(), $data);
		$place = $model->Model();

		$place->author = $author;
		$place->participants[] = $author;

		if (!$model->Validate())
			return Quark::Response(array('status' => 400));

		$model->Save();

		return Quark::Response(array(
			'status' => 200,
			'user' => $model->Model()
		));
	}
}