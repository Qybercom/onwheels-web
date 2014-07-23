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

/**
 * Class CreateService
 * @package Services\Place
 */
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
	 * Request: {"date":"YYYY-MM-DD","name":"string","description":"string","participants":[],"navpoints":[],"position":[]}
	 * Response: {"status":"200|400"}
	 *
	 * @note At different use cases, some keys can be nullable
	 *
	 * Statuses:
	 * 200 - OK
	 * 400 - Validation error
	 *
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

		return Quark::Response(array('status' => 200));
	}
}