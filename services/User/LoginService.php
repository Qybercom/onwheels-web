<?php
namespace Services\User;

use Quark\Quark;
use Quark\QuarkJSONIOProcessor;

use Quark\IQuarkGetService;
use Quark\IQuarkPostService;
use Quark\IQuarkCustomProcessorService;

use Quark\Extensions\Mongo\Model;

use Models\User;

use Quark\Extensions\Facebook\User as Facebook;

/**
 * Class LoginService
 * @package Services\User
 */
class LoginService implements IQuarkGetService, IQuarkPostService, IQuarkCustomProcessorService {
	/**
	 * @return \Quark\IQuarkIOProcessor
	 */
	public function Processor () {
		return new QuarkJSONIOProcessor();
	}

	/**
	 * @param mixed $data
	 * @return mixed|string
	 */
	public function Get ($data) {
		$profile = Facebook::Profile();

		if ($profile == null)
			Quark::Redirect(Facebook::Login());

		$model = $this->_model($profile);

		if ($model == null)
			return 'Error occurred while authorizing';

		$user = $model->Model();

		return 'Welcome ' . $user->first_name . ' ' . $user->last_name . ' ' . Facebook::Session();
	}

	/**
	 * Endpoint for API calls from mobile devices
	 *
	 * Request: {"id":"your_facebook_id","token":"your_facebook_access_token"}
	 * Response: {"status":"200|400|404"}
	 *
	 * Statuses:
	 * 200 - OK
	 * 400 - Login user not found in the database or database problem
	 * 404 - User not found on Facebook
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function Post ($data) {
		$input = Quark::DataArray($data, array(
			'id' => '',
			'token' => ''
		));

		Facebook::Session($input['token']);

		$profile = Facebook::Profile($input['id']);

		if ($profile == null)
			return Quark::Response(array(
				'status' => 404
			));

		$model = $this->_model($profile);

		return Quark::Response(array(
			'status' => $model != null && Quark::Login($model) ? 200 : 400
		));
	}

	/**
	 * @param $profile
	 * @return Model|null
	 */
	private function _model ($profile) {
		$model = new Model(new User(), $profile);

		$record = Model::Find('User', array(
			'id' => $profile['id']
		));

		if (sizeof($record) == 0) {
			if (!$model->Validate()) return null;

			$model->Save();
		}

		return $model;
	}
}