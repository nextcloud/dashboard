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
 * @contributor tuxedo-rb | TUXEDO Computers GmbH | https://www.tuxedocomputers.com
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

use OC\Security\SecureRandom;
use OCA\DAV\CalDAV;
use OCA\DAV\Connector\Sabre\Principal;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IDBConnection;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use OCP\IGroupManager;
use OCP\ILogger;


/**
 * Description of CalendarController
 *
 * @author regio iT gesellschaft für informationstechnologie mbh>
 */
class CalendarController extends Controller {

	const DASHBOARD_LUM_TRESHOULD   = 140.0000;
	const DASHBOARD_LUM_MULTI_RED   =   0.2125;
	const DASHBOARD_LUM_MULTI_GREEN =   0.7154;
	const DASHBOARD_LUM_MULTI_BLUE  =   0.0721;

	/** @var IURLGenerator */
	private $urlGenerator;

	/** @var string */
	private $userId;

	/** @var IUserManager */
	private $userManager;

	/** @var Principal */
	private $principalBackend;

	/** @var CalDAV\CalDavBackend */
	private $calDavBackend;

	/** @var IDBConnection */
	private $db;

	/** @var SecureRandom */
	private $secureRandom;

	/** @var EventDispatcherInterface */
	private $dispatcher;

	/** @var IGroupManager */
	private $groupManager;

	/** @var ILogger */
	private $logger;

	/**
	 * CalendarController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IURLGenerator $urlGenerator
	 * @param IUserManager $userManager
	 * @param $userId
	 * @param Principal $principalBackend
	 * @param IDBConnection $db
	 * @param EventDispatcherInterface $dispatcher
	 * @param SecureRandom $secureRandom
	 * @param IGroupManager $groupManager
	 * @param ILogger $logger
	 */
	public function __construct(
		$appName, IRequest $request, IURLGenerator $urlGenerator, IUserManager $userManager,
		$userId, Principal $principalBackend, IDBConnection $db,
		EventDispatcherInterface $dispatcher, SecureRandom $secureRandom
		,
		IGroupManager $groupManager,
		ILogger $logger
	) {
		parent::__construct($appName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->userManager = $userManager;
		$this->principalBackend = $principalBackend;
		$this->db = $db;
		$this->userId = $userId;
		$this->secureRandom = $secureRandom;
		$this->dispatcher = $dispatcher;
		$this->groupManager = $groupManager;
		$this->logger = $logger;
		$this->calDavBackend = new CalDAV\CalDavBackend(
			$this->db, $this->principalBackend, $this->userManager,
			// we need a groupmanager-obj here as 4th constructor parameter
			$this->groupManager,
			$this->secureRandom,
			// we need a logger-obj here as 6th constructor parameter
			$this->logger,
			$this->dispatcher
		);
	}


	/**
	 * load Events
	 *
	 * @return TemplateResponse
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getEventSources() {
		$calendars = $this->calDavBackend->getCalendarsForUser("principals/users/" . $this->userId);

		foreach ($calendars as $calendar) {
			// luminance threshold source: https://hacks.mozilla.org/2018/07/dark-theme-darkening-better-theming-for-firefox-quantum/
			$lum = static::DASHBOARD_LUM_TRESHOULD + 1.0000;
			$calBackgroundColor = $calendar['{http://apple.com/ns/ical/}calendar-color'];
			if(!isset($calBackgroundColor)) {
				$calBackgroundColor = '#3A87AD'; // standard background color of private events
			}
			// six digit hex notation?
			if (preg_match('/^[#][0-9A-F]{6}$/i', $calBackgroundColor) === 1) {
				$rColorInt = hexdec(substr($calBackgroundColor, 1, 2));
				$gColorInt = hexdec(substr($calBackgroundColor, 3, 2));
				$bColorInt = hexdec(substr($calBackgroundColor, 5, 2));
				$lum = (static::DASHBOARD_LUM_MULTI_RED * $rColorInt)
					+ (static::DASHBOARD_LUM_MULTI_GREEN * $gColorInt)
					+ (static::DASHBOARD_LUM_MULTI_BLUE * $bColorInt)
				;
			}
			$textColor = '#FFFFFF'; // white font color
			// $lum values over 140 will be classified as bright background color
			if ($lum > static::DASHBOARD_LUM_TRESHOULD) {
				$textColor = '#000000'; // black font color
			}
			$eventSources[] = [
				'url' => $this->urlGenerator->linkToRoute(
					'dashboard.calendar.get_events',
					['calendarid' => $calendar['id']]
				),
				'backgroundColor' => $calendar['{http://apple.com/ns/ical/}calendar-color'],
				'borderColor'     => '#888',
				'textColor'       => $textColor,
				'cache'           => true,
			];
		}

		$eventSources[] = [
			'url'             => $this->urlGenerator->linkToRoute(
				'dashboard.calendar.get_events', [
												   'calendarid' => 'shared_events',
											   ]
			),
			'backgroundColor' => '#1D2D44',
			'borderColor'     => '#888',
			'textColor'       => 'white',
			'editable'        => 'false',
		];

		$params = [
			'eventSources' => $eventSources,
		];
		$response = new TemplateResponse($this->appName, 'jsonp', $params, 'blank');
		$response->addHeader('Content-Type', 'application/javascript');

		return $response;
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $calendarid
	 *
	 * @return DataResponse $data
	 */
	public function getEvents($calendarid) {
		// get Events from Calendar
		$events = $this->calDavBackend->getCalendarObjects($calendarid);

		// return
		$data = [];
		//parse caldav-events into fullcalendar-syntax
		foreach ($events as $event) {
			if ($event['component'] !== 'vevent') {
				continue;
			}
			$result = $this->calDavBackend->getCalendarObject($event['calendarid'], $event['uri']);
			// allDay-Event?
			$allDay = false;
			// relevant Information event
			$calendardata = $result["calendardata"];
			$parts = explode("\r\n", $calendardata);
			$newEventArray = [];
			//parse Event-Information into Array
			foreach ($parts as $element) {
				// exception for calendar text with containing double-point chars
				// (at example 'Meeting 10:45')
				if (substr_count($element, ':') > 1
					&& (strpos($element, 'SUMMARY:') === 0)) {
					$part1 = substr($element, 0, strpos($element, ":"));
					$part2 = substr($element, strpos($element, ":") + 1);
				} else {
					$part1 = substr($element, 0, strrpos($element, ":"));
					$part2 = substr(
						$element, strrpos($element, ":") + 1,
						strlen($element) - strrpos($element, ":") + 1
					);
				}
				$newEventArray = $newEventArray + [$part1 => $part2];
			}
			$fixeslastmodified = strtotime(date("Y-m-d H:i:s"));
			if (isset($newEventArray["LAST-MODIFIED"])) {
				$lastmodified = $newEventArray["LAST-MODIFIED"];
				$fixeslastmodified =
					date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $lastmodified)));
			}
			//Allday-Event = False;
			if (array_key_exists('TZID', $newEventArray)) {
				// NOTE: there is still a problem if timezone is
				// different from DTSTART and DTEND
				$tzid = $newEventArray['TZID'];
				$fixedstart = "";
				$fixedend = "";
				if (isset($newEventArray["DTSTART;TZID=" . $tzid])) {
					$datestart = $newEventArray["DTSTART;TZID=" . $tzid];
					$fixedstart = date(
						'Y-m-d H:i:s',
						strtotime(
							str_replace(
								'-',
								'/',
								$datestart
							)
						)
					);
				}
				if (isset($newEventArray["DTEND;TZID=" . $tzid])) {
					$dateend = $newEventArray["DTEND;TZID=" . $tzid];
					$fixedend = date(
						'Y-m-d H:i:s',
						strtotime(
							str_replace(
								'-',
								'/',
								$dateend
							)
						)
					);
				}
				//Allday-Event = true;
			} else {
				$fixeslastmodified = strtotime(date("Y-m-d H:i:s"));
				$allDay = true;
				$fixedstart = "";
				$fixedend = "";
				if (isset($newEventArray["DTSTART;VALUE=DATE"])) {
					$datestart = $newEventArray["DTSTART;VALUE=DATE"];
					$fixedstart = date('Y-m-d', strtotime(str_replace('-', '/', $datestart)));
				}
				if (isset($newEventArray["DTEND;VALUE=DATE"])) {
					$dateend = $newEventArray["DTEND;VALUE=DATE"];
					$fixedend = date('Y-m-d', strtotime(str_replace('-', '/', $dateend)));
				}
			}
			// Description - check if null
			$description = "";
			if (isset($newEventArray["DESCRIPTION"])) {
				$description = $newEventArray["DESCRIPTION"];
			}
			if ($fixedstart != "" && $fixedend != "") {
				// declare new Event-Format with relevant Information
				$mynewEvent = array(
					"allDay"       => $allDay,
					"description"  => $description,
					"end"          => $fixedend,
					"id"           => $result["id"],
					"lastmodified" => $fixeslastmodified,
					"start"        => $fixedstart,
					"title"        => $newEventArray["SUMMARY"]
				);
				$mynewEvent = [$mynewEvent];
				$data = array_merge($data, $mynewEvent);
			}
			if (array_key_exists("RRULE", $newEventArray)) {
				$rule = $newEventArray["RRULE"];
				// Check if serial event
				$check = strrpos($rule, "BYDAY");
				if ($check == false) {
					//daily, weeekly, monthly, yearly
					$rule = $newEventArray["RRULE"];
					$intervalDay = strrpos($rule, "DAILY");
					$intervalWeek = strrpos($rule, "WEEKLY");
					$intervalMonth = strrpos($rule, "MONTHLY");
					$intervalMYear = -1;
					if (strrpos($rule, "YEARLY") > -1 && strrpos($rule, "BYDAY") < 0) {
						$intervalMYear = strrpos($rule, "YEARLY");
					}
					// CountRule + //Interval
					$intCountRule = 0;
					$intInterval = 1;
					if (strrpos($rule, "COUNT=") > -1) {
						if (strrpos($rule, "INTERVAL=") > -1) {
							$countrule = substr(
								$rule, strrpos($rule, "COUNT=") + 6,
								strlen($rule) - strrpos($rule, "INTERVAL=") - 1
							);
							$interVal = substr(
								$rule, strrpos($rule, "INTERVAL=") + 9,
								strlen($rule) - strrpos($rule, "INTERVAL=") + 9
							);
							$intInterval = intval($interVal);
						} else {
							$countrule = substr(
								$rule, strrpos($rule, "COUNT=") + 6,
								strlen($rule) - strrpos($rule, "COUNT=") + 6
							);
						}
						$intCountRule = intval($countrule);
					} else {
						$intCountRule = 52;
					}

					$days = 0;
					if ($intervalDay > -1) {
						$days = 1 * $intInterval;
					} else if ($intervalWeek > -1) {
						$days = 7 * $intInterval;
					} else if ($intervalMYear > -1) {
						$days = 356 * $intInterval;
					}
					for ($i = 0; $i < $intCountRule; $i++) {
						if ($intervalMonth > -1) {
							$month = 1 * $intInterval;
							$addMonth = ($i + 1) * $month;
							$newfixedDayStart = date(
								'Y-m-d H:i:s', strtotime(
												 str_replace('-', '/', $fixedstart) . " +"
												 . str_replace(
													 '-', '/', $addMonth
												 ) . " month"
											 )
							);
							$newfixedDayEnd = date(
								'Y-m-d H:i:s', strtotime(
												 str_replace('-', '/', $fixedend) . " +"
												 . str_replace(
													 '-', '/', $addMonth
												 ) . " month"
											 )
							);
						} else {
							$addDays = ($i + 1) * $days;
							$newfixedDayStart = date(
								'Y-m-d H:i:s', strtotime(
												 str_replace('-', '/', $fixedstart) . " +"
												 . str_replace(
													 '-', '/', $addDays
												 ) . " days"
											 )
							);
							$newfixedDayEnd = date(
								'Y-m-d H:i:s', strtotime(
												 str_replace('-', '/', $fixedend) . " +"
												 . str_replace(
													 '-', '/', $addDays
												 ) . " days"
											 )
							);
						}
						$mynewEvent = array(
							"allDay"       => $allDay,
							"description"  => $description . " - Serientermin",
							"end"          => $newfixedDayEnd,
							"id"           => $result["id"],
							"lastmodified" => mktime($fixeslastmodified),
							"start"        => $newfixedDayStart,
							"title"        => $newEventArray["SUMMARY"]
						);
						$mynewEvent = [$mynewEvent];
						$data = array_merge($data, $mynewEvent);
					}
				}
			}
		}

		// return Event-Information in Array
		return new DataResponse($data);
	}

}
