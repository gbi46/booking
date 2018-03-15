<?php
/* * ********************************************************************************************
 *								Open Real Estate
 *								----------------
 * 	version				:	V1.23.0
 * 	copyright			:	(c) 2015 Monoray
 * 							http://monoray.net
 *							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

require_once dirname(dirname(__FILE__)).'/services/FacebookOAuthService.php';

class CustomFBService extends FacebookOAuthService {
	/**
	 * https://developers.facebook.com/docs/authentication/permissions/
	 */
	protected $scope = 'email,user_birthday,user_hometown,user_location';
	/**
	 * http://developers.facebook.com/docs/reference/api/user/
	 *
	 * @see FacebookOAuthService::fetchAttributes()
	 */
	protected function fetchAttributes() {
		$info = $this->makeSignedRequest('https://graph.facebook.com/v2.8/me', array(
			'query' => array(
				'fields' => join(',', array(
					'id',
					'name',
					'link',
					'email',
					'verified',
					'first_name',
					'last_name',
					'gender',
					'birthday',
					'hometown',
					'location',
					'locale',
					'timezone',
					'updated_time',
				))
			)
		));

		$this->attributes['id'] = $info->id;
		$this->attributes['firstName'] = (isset($info->first_name) && $info->first_name) ? $info->first_name : '';
		$this->attributes['email'] = (isset($info->email) && $info->email) ? $info->email : '';
		$this->attributes['mobilePhone'] = '';
		$this->attributes['homePhone'] = '';
		$this->attributes['url'] = (isset($info->link) && $info->link) ? $info->link : '';
	}
}
