<?php


namespace OCA\Dashboard;


use OCA\Dashboard\Model\WidgetRequest;

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
	 * @param array $config
	 */
	public function loadWidget($config);


	/**
	 * @return array
	 */
	public function widgetSetup();


	/**
	 * @param WidgetRequest $request
	 */
	public function requestWidget(WidgetRequest $request);

}