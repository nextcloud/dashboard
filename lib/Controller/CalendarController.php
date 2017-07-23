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


/**
 * Description of CalendarController
 *
 * @author regio iT gesellschaft für informationstechnologie mbh>
 */
class CalendarController extends Controller {

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
	 */
	public function __construct(
		$appName, IRequest $request, IURLGenerator $urlGenerator, IUserManager $userManager,
		$userId, Principal $principalBackend, IDBConnection $db,
		EventDispatcherInterface $dispatcher, SecureRandom $secureRandom
	) {
		parent::__construct($appName, $request);
		$this->urlGenerator = $urlGenerator;
		$this->userManager = $userManager;
		$this->principalBackend = $principalBackend;
		$this->db = $db;
		$this->userId = $userId;
		$this->secureRandom = $secureRandom;
		$this->dispatcher = $dispatcher;
		$this->calDavBackend = new CalDAV\CalDavBackend(
			$this->db, $this->principalBackend, $this->userManager, $this->secureRandom,
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
			$eventSources[] = [
				'url'             => $this->urlGenerator->linkToRoute(
					'dashboard.calendar.get_events', [
													   'calendarid' => $calendar['id'],
												   ]
				),
				'backgroundColor' => $calendar['{http://apple.com/ns/ical/}calendar-color'],
				'borderColor'     => '#888',
				'textColor'       => '#FFF',
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

			$result = $this->calDavBackend->getCalendarObject($event['calendarid'], $event['uri']);
			// allDay-Event?
			$allDay = false;
			// relevant Information event
			$calendardata = $result["calendardata"];
			$parts = explode("\r\n", $calendardata);
			$newEventArray = [];
			//parse Event-Information into Array
			foreach ($parts as $element) {
				$part1 = substr($element, 0, strrpos($element, ":"));
				$part2 = substr(
					$element, strrpos($element, ":") + 1,
					strlen($element) - strrpos($element, ":") + 1
				);
				$newEventArray = $newEventArray + [$part1 => $part2];
			}
			$fixeslastmodified = strtotime(date("Y-m-d H:i:s"));
			if (isset($newEventArray["LAST-MODIFIED"])) {
				$lastmodified = $newEventArray["LAST-MODIFIED"];
				$fixeslastmodified =
					date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $lastmodified)));
			}
			//Allday-Event = False;
			if (array_key_exists("DTSTART;TZID=Europe/Berlin", $newEventArray)) {
				$datestart = $newEventArray["DTSTART;TZID=Europe/Berlin"];
				$dateend = $newEventArray["DTEND;TZID=Europe/Berlin"];
				$fixedstart = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $datestart)));
				$fixedend = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $dateend)));
			} //Allday-Event = true;
			else {
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
