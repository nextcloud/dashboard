<?php


namespace OCA\Dashboard;


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
	 * @param array $config
	 */
	public function loadWidget($config);


	/**
	 * @return array
	 */
	public function widgetSetup();


}