<?php


namespace OCA\Dashboard;


use OCA\Dashboard\Model\WidgetRequest;
use OCA\Dashboard\Model\WidgetSettings;

interface IDashboardWidget {

	/**
	 * @return string
	 */
	public function getId();


	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @return string
	 */
	public function getDescription();


	/**
	 * @return array
	 */
	public function getTemplate();


	/**
	 * @param WidgetSettings $settings
	 */
	public function loadWidget($settings);


	/**
	 * @return array
	 */
	public function widgetSetup();


	/**
	 * @param WidgetRequest $request
	 */
	public function requestWidget(WidgetRequest $request);

}