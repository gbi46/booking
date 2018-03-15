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
class BsAlert extends CWidget
{

	/**
	 * @var array the alerts configurations.
	 */
	public $alerts;
	/**
	 * @var string|boolean the close link text. If this is set false, no close link will be displayed.
	 */
	public $closeText = '&times;';
	/**
	 * @var boolean indicates whether the alert should be an alert block. Defaults to 'true'.
	 */
	public $block = true;
	/**
	 * @var boolean indicates whether alerts should use transitions. Defaults to 'true'.
	 */
	public $fade = true;
	/**
	 * @var string[] the Javascript event handlers.
	 */
	public $events = array();
	/**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id'] = $this->getId();

		if (is_string($this->alerts))
			$this->alerts = array($this->alerts);

		// Display all alert types by default.
		if (!isset($this->alerts))
			$this->alerts = array(BsHtml::ALERT_COLOR_DANGER, BsHtml::ALERT_COLOR_ERROR, BsHtml::ALERT_COLOR_INFO,
				BsHtml::ALERT_COLOR_SUCCESS, BsHtml::ALERT_COLOR_WARNING);
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$id = $this->htmlOptions['id'];

		foreach ($this->alerts as $type)
		{



			if (Yii::app()->user->hasFlash($type))
			{

				$alertFunction = $this->block ? 'blockAlert' : 'alert';

				$validTypes = array(BsHtml::ALERT_COLOR_DANGER, BsHtml::ALERT_COLOR_ERROR, BsHtml::ALERT_COLOR_INFO,
					BsHtml::ALERT_COLOR_SUCCESS, BsHtml::ALERT_COLOR_WARNING);

				if (in_array($type, $validTypes))
					$alertColor = in_array($type, $validTypes) ? $type : BsHtml::ALERT_COLOR_DEFAULT;


				if ($this->closeText !== false)
					$this->htmlOptions['closeText'] = $this->closeText;


				echo BsHtml::alert($type, Yii::app()->user->getFlash($type), $this->htmlOptions);
			}
		}

	}
}
