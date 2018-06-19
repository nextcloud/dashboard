<?php


namespace OCA\Dashboard;


interface IDashboardPlugin {

	/**
	 * @return string
	 */
	public function getId();


	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @return array
	 */
	public function getTemplate();


	/**
	 * @param array $config
	 */
	public function loadPlugin($config);


	/**
	 * @return array
	 */
	public function pluginSetup();


}