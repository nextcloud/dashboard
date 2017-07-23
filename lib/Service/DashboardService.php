<?php

/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 *
 * @copyright regio iT 2017
 */

namespace OCA\Dashboard\Service;

use Exception;
use OCP\IConfig;
use OCP\IGroupManager;
use OCP\Security\ICrypto;

/**
 * Description of DashboardService
 */
class DashboardService {
	/**
	 * @var string
	 */
	private $appName;

	/**
	 * @var IConfig
	 */
	private $config;

	/**
	 * @var ICrypto
	 */
	private $crypto;

	/**
	 * @var IGroupManager
	 */
	private $groupManager;

	/**
	 * @var string
	 */
	private $userId;

	/**
	 * DashboardService constructor.
	 *
	 * @param $appName
	 * @param IConfig $config
	 * @param ICrypto $crypto
	 * @param IGroupManager $groupManager
	 * @param $userId
	 *
	 */
	public function __construct(
		$appName, IConfig $config, ICrypto $crypto, IGroupManager $groupManager, $userId
	) {
		$this->appName = $appName;
		$this->config = $config;
		$this->crypto = $crypto;
		$this->groupManager = $groupManager;
		$this->userId = $userId;
	}

	public function getAppValue($key, $default = '') {
		return $this->config->getAppValue($this->appName, $key, $default);
	}

	public function getSystemValue($key, $default = '') {
		return $this->config->getSystemValue($key, $default);
	}

	public function getUserValue($key, $default = '') {
		return $this->config->getUserValue($this->userId, $this->appName, $key, $default);
	}

	public function getUserValueWithCrypto($key, $default = '') {
		try {
			return $this->crypto->decrypt(
				$this->config->getUserValue($this->userId, $this->appName, $key, $default)
			);
		} catch (Exception $e) {
			return $default;
		}
	}

	public function isAdmin() {
		return $this->groupManager->isAdmin($this->userId);
	}

	public function isInGroup($group) {
		return $this->groupManager->isInGroup($this->userId, $group);
	}

	public function setAppValue($key, $value) {
		$this->config->setAppValue($this->appName, $key, $value);
	}

	public function setSystemValue($key, $value) {
		$this->config->setSystemValue($key, $value);
	}

	public function setUserValue($key, $value, $preCondition = null) {
		$this->config->setUserValue($this->userId, $this->appName, $key, $value, $preCondition);
	}

	public function setUserValueWithCrypto($key, $value, $preCondition = null) {
		if (!empty($value)) {
			$value = $this->crypto->encrypt($value);
		}
		if (!empty($preCondition)) {
			$preCondition = $this->crypto->encrypt($preCondition);
		}
		$this->config->setUserValue($this->userId, $this->appName, $key, $value, $preCondition);
	}
}