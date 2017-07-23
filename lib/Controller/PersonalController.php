<?php

/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author regio iT gesellschaft für informationstechnologie mbh
 * @copyright regio iT 2017
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Dashboard\Controller;

use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IL10N;
use OCP\IRequest;

/**
 * Description of PersonalController
 */
class PersonalController extends Controller {

	const INBOX_REMOTE_SYSTEM_NAME = 'inbox_remote_system_name';
	const INBOX_PORT = 'inbox_port';
	const INBOX_SSL = 'inbox_ssl';
	const INBOX_USERNAME = 'inbox_username';
	const INBOX_PASSWORD = 'inbox_password';

	/** @var DashboardService */
	private $dashboardService;

	/** @var \OCP\IL10N */
	protected $l10n;

	/**
	 * PersonalController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param DashboardService $dashboardService
	 */
	public function __construct(
		$appName, IRequest $request, DashboardService $dashboardService, IL10N $l10n
	) {
		parent::__construct($appName, $request);
		$this->dashboardService = $dashboardService;
		$this->l10n = $l10n;
	}

	/**
	 * load User-Settings
	 *
	 * @return TemplateResponse
	 * @NoAdminRequired
	 */
	public function index() {
		$params = [
			static::INBOX_REMOTE_SYSTEM_NAME => $this->dashboardService->getUserValue(
				static::INBOX_REMOTE_SYSTEM_NAME
			),
			static::INBOX_PORT               => $this->dashboardService->getUserValue(
				static::INBOX_PORT
			),
			static::INBOX_SSL                => $this->dashboardService->getUserValue(
				static::INBOX_SSL
			),
			static::INBOX_USERNAME           => $this->dashboardService->getUserValue(
				static::INBOX_USERNAME
			),
			static::INBOX_PASSWORD           => $this->dashboardService->getUserValueWithCrypto(
				static::INBOX_PASSWORD
			),
		];

		return new TemplateResponse($this->appName, 'personal', $params, 'blank');
	}

	/**
	 * save User-Settings
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 */
	public function save() {
		$definition = [
			static::INBOX_REMOTE_SYSTEM_NAME => [
				'filter'  => FILTER_VALIDATE_REGEXP,
				'flags'   => FILTER_NULL_ON_FAILURE,
				'options' => [
					'regexp' => '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])|(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/'
				],
			],
			static::INBOX_PORT               => [
				'filter'  => FILTER_VALIDATE_REGEXP,
				'flags'   => FILTER_NULL_ON_FAILURE,
				'options' => [
					'regexp' => '/^6553[0-5]|655[0-2][0-9]|65[0-4][0-9]{2}|6[0-4][0-9]{3}|[1-5][0-9]{4}|[1-9][0-9]{3}|[1-9][0-9]{2}|[1-9]?[0-9]$/',
				],
			],
			static::INBOX_SSL                => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::INBOX_USERNAME           => [
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::INBOX_PASSWORD           => [
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
		];
		$input = filter_input_array(INPUT_POST, $definition);

		$errors = [];
		foreach ($input as $key => $value) {
			if (!isset($value)) {
				$errors[] = $key;
			}
		}

		$success = empty($errors);
		if ($success) {
			$this->dashboardService->setUserValue(
				static::INBOX_REMOTE_SYSTEM_NAME, $input[static::INBOX_REMOTE_SYSTEM_NAME]
			);
			$this->dashboardService->setUserValue(static::INBOX_PORT, $input[static::INBOX_PORT]);
			$this->dashboardService->setUserValue(
				static::INBOX_SSL, (int)$input[static::INBOX_SSL]
			);
			$this->dashboardService->setUserValue(
				static::INBOX_USERNAME, $input[static::INBOX_USERNAME]
			);
			$this->dashboardService->setUserValueWithCrypto(
				static::INBOX_PASSWORD, $input[static::INBOX_PASSWORD]
			);
		}

		return new DataResponse(
			array(
				'data' => array(
					'message' => (string)$this->l10n->t('Settings have been updated.'),
				),
			)
		);
	}
}