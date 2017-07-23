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


use DateInterval;
use OC_Helper;
use OCA\Dashboard\Db\DashboardSettingsMapper;
use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Template;


/**
 * Description of PageController
 */
class PageController extends Controller {

	/** @var DashboardSettingsMapper */
	private $dashboardSettingsMapper;

	/** @var DashboardService */
	private $dashboardService;

	/** @var IURLGenerator */
	private $urlGenerator;

	/**
	 * PageController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param DashboardSettingsMapper $dashboardSettingsMapper
	 * @param DashboardService $dashboardService
	 * @param IURLGenerator $urlGenerator
	 */
	public function __construct(
		$appName, IRequest $request, DashboardSettingsMapper $dashboardSettingsMapper,
		DashboardService $dashboardService, IURLGenerator $urlGenerator
	) {
		parent::__construct($appName, $request);
		$this->dashboardSettingsMapper = $dashboardSettingsMapper;
		$this->dashboardService = $dashboardService;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		$canCreateAnnouncements = $this->dashboardService->isInGroup('News');
		$storageInfo = OC_Helper::getStorageInfo('/');

		$csp = new ContentSecurityPolicy();
		$csp->addAllowedImageDomain('data:');
		$csp->addAllowedScriptDomain('*');
		$csp->addAllowedChildSrcDomain("*");
		$csp->addAllowedMediaDomain("*");
		$csp->addAllowedFontDomain("*");
		$csp->addAllowedScriptDomain("*");
		$csp->allowInlineStyle(true);
		$csp->allowEvalScript(true);
		$csp->addAllowedFrameDomain("*");
		$csp->allowInlineScript(true);
		$showActivity = 1;
		$showInbox = 1;
		$showAnnouncement = 1;
		$showCalendar = 1;
		$showWideActivity = 0;
		$showWideInbox = 0;
		$showWideAnnouncement = 0;
		$showWideCalendar = 0;
		$activityPosition = 1;
		$inboxPosition = 2;
		$announcementPosition = 3;
		$calendarPosition = 4;
		$limit = 20;
		$dashboardSettings = $this->dashboardSettingsMapper->findAll($limit);
		foreach ($dashboardSettings as $setting) {
			$key = $setting->key;
			switch ($key) {
				case 'show_activity':
					$showActivity = (int)$setting->value;
					break;
				case 'show_inbox':
					$showInbox = (int)$setting->value;
					break;
				case 'show_announcement':
					$showAnnouncement = (int)$setting->value;
					break;
				case 'show_calendar':
					$showCalendar = (int)$setting->value;
					break;
				case 'show_wide_activity':
					$showWideActivity = (int)$setting->value;
					break;
				case 'show_wide_inbox':
					$showWideInbox = (int)$setting->value;
					break;
				case 'show_wide_announcement':
					$showWideAnnouncement = (int)$setting->value;
					break;
				case 'show_wide_calendar':
					$showWideCalendar = (int)$setting->value;
					break;
				case 'activity_position':
					$activityPosition = (int)$setting->value;
					break;
				case 'inbox_position':
					$inboxPosition = (int)$setting->value;
					break;
				case 'announcement_position':
					$announcementPosition = (int)$setting->value;
					break;
				case 'calendar_position':
					$calendarPosition = (int)$setting->value;
					break;
			}
		}
		$params = [
			'can_create_announcements' => $canCreateAnnouncements,
			'isAdmin'                  => $this->dashboardService->isAdmin(),
			'show_activity'            => $showActivity,
			'show_inbox'               => $showInbox,
			'show_announcement'        => $showAnnouncement,
			'show_calendar'            => $showCalendar,
			'show_wide_activity'       => $showWideActivity,
			'show_wide_inbox'          => $showWideInbox,
			'show_wide_announcement'   => $showWideAnnouncement,
			'show_wide_calendar'       => $showWideCalendar,
			'activity_position'        => $activityPosition,
			'inbox_position'           => $inboxPosition,
			'announcement_position'    => $announcementPosition,
			'calendar_position'        => $calendarPosition,

			'default_announcement_expiration_value' => date_create()
				->add(new DateInterval('P1M'))
				->format('Y-m-d'),

			'panel_0'                              => $this->getPanel(
				'panel_0', $this->getPaneltoOrder(
				1, $activityPosition, $inboxPosition, $announcementPosition, $calendarPosition
			)
			),
			'panel_1'                              => $this->getPanel(
				'panel_1', $this->getPaneltoOrder(
				2, $activityPosition, $inboxPosition, $announcementPosition, $calendarPosition
			)
			),
			'panel_2'                              => $this->getPanel(
				'panel_2', $this->getPaneltoOrder(
				3, $activityPosition, $inboxPosition, $announcementPosition, $calendarPosition
			)
			),
			'panel_3'                              => $this->getPanel(
				'panel_3', $this->getPaneltoOrder(
				4, $activityPosition, $inboxPosition, $announcementPosition, $calendarPosition
			)
			),
			'storage_info'                         => [
				'relative' => $storageInfo['relative'],
				'quota'    => [
					Template::human_file_size($storageInfo['used']),
					Template::human_file_size($storageInfo['total']),
				],
			],
			'dashboard.calendar.get_event_sources' => $this->urlGenerator->linkToRoute(
				'dashboard.calendar.get_event_sources'
			),
		];
		$response = new TemplateResponse($this->appName, 'main', $params);

		return $response->setContentSecurityPolicy($csp);
	}

	private function getPaneltoOrder(
		$order, $activityPosition, $inboxPosition, $announcementPosition, $calendarPosition
	) {
		if ($order == $activityPosition) {
			return 'activities';
		}
		if ($order == $inboxPosition) {
			return 'inbox';
		}
		if ($order == $announcementPosition) {
			return 'announcements';
		}
		if ($order == $calendarPosition) {
			return 'calendar';
		}
	}

	private function getPanel($key, $default = '') {
		$value = $this->dashboardService->getAppValue($key, $default);
		if (in_array($value, ['activities', 'announcements', 'calendar', 'inbox'])) {
			return $value;
		} else {
			return 'blank';
		}
	}
}