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

use OCA\Dashboard\Db\DashboardSettingsMapper;
use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IL10N;
use OCP\IRequest;

/**
 * Description of AdminController
 */
class AdminController extends Controller {

	const SHOW_ACTIVITY = 'show_activity';
	const SHOW_INBOX = 'show_inbox';
	const SHOW_ANNOUNCEMENT = 'show_announcement';
	const SHOW_CALENDAR = 'show_calendar';
	const SHOW_WIDE_ACTIVITY = 'show_wide_activity';
	const SHOW_WIDE_INBOX = 'show_wide_inbox';
	const SHOW_WIDE_ANNOUNCEMENT = 'show_wide_announcement';
	const SHOW_WIDE_CALENDAR = 'show_wide_calendar';
	const ACTIVITY_POSITION = 'activity_position';
	const INBOX_POSITION = 'inbox_position';
	const ANNOUNCEMENT_POSITION = 'announcement_position';
	const CALENDAR_POSITION = 'calendar_position';
	const ANNOUNCEMENT_GROUP = 'announcement_group';


	/** @var DashboardSettingsMapper */
	private $dashboardSettingsMapper;

	/** @var DashboardService */
	private $dashboardService;

	/** @var \OCP\IL10N */
	protected $l10n;

	/**
	 * AdminController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param DashboardSettingsMapper $dashboardSettingsMapper
	 * @param DashboardService $dashboardService
	 * @param IL10N $l10n
	 */
	public function __construct(
		$appName, IRequest $request, DashboardSettingsMapper $dashboardSettingsMapper,
		DashboardService $dashboardService, IL10N $l10n
	) {
		parent::__construct($appName, $request);
		$this->dashboardSettingsMapper = $dashboardSettingsMapper;
		$this->dashboardService = $dashboardService;
		$this->l10n = $l10n;
	}

	/**
	 * save Admin-Settings
	 *
	 * @return DataResponse
	 */
	public function save() {
		$definition = [
			static::SHOW_ACTIVITY          => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_INBOX             => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_ANNOUNCEMENT      => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_CALENDAR          => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_WIDE_ACTIVITY     => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_WIDE_INBOX        => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_WIDE_ANNOUNCEMENT => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::SHOW_WIDE_CALENDAR     => [
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::ACTIVITY_POSITION      => [
				'flags' => FILTER_NULL_ON_FAILURE,
			],
			static::INBOX_POSITION         => [
				'flags' => FILTER_NULL_ON_FAILURE,
			],
			static::ANNOUNCEMENT_POSITION  => [
				'flags' => FILTER_NULL_ON_FAILURE,
			],
			static::CALENDAR_POSITION      => [
				'flags' => FILTER_NULL_ON_FAILURE,
			],
			static::ANNOUNCEMENT_GROUP      => [
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_NULL_ON_FAILURE,
			]

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
			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(1));
			$dashboardSettings->setId(1);
			$dashboardSettings->setKey('show_activity');
			$dashboardSettings->setValue($input[static::SHOW_ACTIVITY]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(2));
			$dashboardSettings->setId(2);
			$dashboardSettings->setKey('show_inbox');
			$dashboardSettings->setValue($input[static::SHOW_INBOX]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(3));
			$dashboardSettings->setId(3);
			$dashboardSettings->setKey('show_announcement');
			$dashboardSettings->setValue($input[static::SHOW_ANNOUNCEMENT]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(4));
			$dashboardSettings->setId(4);
			$dashboardSettings->setKey('show_calendar');
			$dashboardSettings->setValue($input[static::SHOW_CALENDAR]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(5));
			$dashboardSettings->setId(5);
			$dashboardSettings->setKey('show_wide_activity');
			$dashboardSettings->setValue($input[static::SHOW_WIDE_ACTIVITY]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(6));
			$dashboardSettings->setId(6);
			$dashboardSettings->setKey('show_wide_inbox');
			$dashboardSettings->setValue($input[static::SHOW_WIDE_INBOX]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(7));
			$dashboardSettings->setId(7);
			$dashboardSettings->setKey('show_wide_announcement');
			$dashboardSettings->setValue($input[static::SHOW_WIDE_ANNOUNCEMENT]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(8));
			$dashboardSettings->setId(8);
			$dashboardSettings->setKey('show_wide_calendar');
			$dashboardSettings->setValue($input[static::SHOW_WIDE_CALENDAR]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(9));
			$dashboardSettings->setId(9);
			$dashboardSettings->setKey('calendar_position');
			$dashboardSettings->setValue($input[static::CALENDAR_POSITION]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(10));
			$dashboardSettings->setId(10);
			$dashboardSettings->setKey('activity_position');
			$dashboardSettings->setValue($input[static::ACTIVITY_POSITION]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(11));
			$dashboardSettings->setId(11);
			$dashboardSettings->setKey('inbox_position');
			$dashboardSettings->setValue($input[static::INBOX_POSITION]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(12));
			$dashboardSettings->setId(12);
			$dashboardSettings->setKey('announcement_position');
			$dashboardSettings->setValue($input[static::ANNOUNCEMENT_POSITION]);
			$this->dashboardSettingsMapper->update($dashboardSettings);

			$dashboardSettings = $this->dashboardSettingsMapper->findOne(intval(13));
			$dashboardSettings->setId(13);
			$dashboardSettings->setKey('announcement_group');
			$dashboardSettings->setValue($input[static::ANNOUNCEMENT_GROUP]);
			$this->dashboardSettingsMapper->update($dashboardSettings);
		}

		return new DataResponse(
			array(
				'data' => array(
					'message' => (string)$this->l10n->t('Settings have been updated.'),
				),
			)
		);
	}

	/**
	 * load Admin-Settings
	 *
	 * @return TemplateResponse
	 */
	public function index() {
		$showActivity = '1';
		$showInbox = '1';
		$showAnnouncement = '1';
		$showCalendar = '1';
		$showWideActivity = '0';
		$showWideInbox = '0';
		$showWideAnnouncement = '0';
		$showWideCalendar = '0';
		$activityPosition = '1';
		$inboxPosition = '2';
		$announcementPosition = '3';
		$calendarPosition = '4';
		$announcementGroup = 'News';


		$limit = 20;
		$dashboardSettings = $this->dashboardSettingsMapper->findAll($limit);
		foreach ($dashboardSettings as $setting) {
			$key = $setting->key;
			switch ($key) {
				case 'show_activity':
					$showActivity = $setting->value;
					break;
				case 'show_inbox':
					$showInbox = $setting->value;
					break;
				case 'show_announcement':
					$showAnnouncement = $setting->value;
					break;
				case 'show_calendar':
					$showCalendar = $setting->value;
					break;
				case 'show_wide_activity':
					$showWideActivity = $setting->value;
					break;
				case 'show_wide_inbox':
					$showWideInbox = $setting->value;
					break;
				case 'show_wide_announcement':
					$showWideAnnouncement = $setting->value;
					break;
				case 'show_wide_calendar':
					$showWideCalendar = $setting->value;
					break;
				case 'activity_position':
					$activityPosition = $setting->value;
					break;
				case 'inbox_position':
					$inboxPosition = $setting->value;
					break;
				case 'announcement_position':
					$announcementPosition = $setting->value;
					break;
				case 'calendar_position':
					$calendarPosition = $setting->value;
					break;
				case 'announcement_group':
					$announcementGroup = $setting->value;
					break;
			}
		}
		$params = [
			static::SHOW_ACTIVITY          => $showActivity,
			static::SHOW_INBOX             => $showInbox,
			static::SHOW_ANNOUNCEMENT      => $showAnnouncement,
			static::SHOW_CALENDAR          => $showCalendar,
			static::SHOW_WIDE_ACTIVITY     => $showWideActivity,
			static::SHOW_WIDE_INBOX        => $showWideInbox,
			static::SHOW_WIDE_ANNOUNCEMENT => $showWideAnnouncement,
			static::SHOW_WIDE_CALENDAR     => $showWideCalendar,
			static::ACTIVITY_POSITION      => $activityPosition,
			static::INBOX_POSITION         => $inboxPosition,
			static::ANNOUNCEMENT_POSITION  => $announcementPosition,
			static::CALENDAR_POSITION      => $calendarPosition,
			static::ANNOUNCEMENT_GROUP     => $announcementGroup
		];

		return new TemplateResponse($this->appName, 'admin', $params, 'blank');
	}


}