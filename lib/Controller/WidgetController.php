<?php declare(strict_types=1);


/**
 * Nextcloud - Dashboard app
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018, Maxence Lange <maxence@artificial-owl.com>
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

use Exception;
use OCA\Dashboard\AppInfo\Application;
use OCA\Dashboard\Model\WidgetRequest;
use OCA\Dashboard\Service\ConfigService;
use OCA\Dashboard\Service\EventsService;
use OCA\Dashboard\Service\MiscService;
use OCA\Dashboard\Service\WidgetsService;
use OCP\AppFramework\Controller;
use OCP\Dashboard\Model\IWidgetEvent;
use OCP\IRequest;

class WidgetController extends Controller {

	/** @var string */
	private $userId;

	/** @var EventsService */
	private $eventsService;

	/** @var ConfigService */
	private $configService;

	/** @var WidgetsService */
	private $widgetsService;

	/** @var MiscService */
	private $miscService;


	/**
	 * WidgetController constructor.
	 *
	 * @param IRequest $request
	 * @param string $userId
	 * @param ConfigService $configService
	 * @param EventsService $eventsService
	 * @param WidgetsService $widgetsService
	 * @param MiscService $miscService
	 */
	public function __construct(
		IRequest $request, $userId, ConfigService $configService, EventsService $eventsService,
		WidgetsService $widgetsService, MiscService $miscService
	) {
		parent::__construct(Application::APP_NAME, $request);

		$this->userId = $userId;
		$this->configService = $configService;
		$this->eventsService = $eventsService;
		$this->widgetsService = $widgetsService;
		$this->miscService = $miscService;
	}


	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @param string $json
	 *
	 * @return array
	 */
	public function requestWidget(string $json): array {

		try {
			$request = WidgetRequest::fromJson($json);
			$this->widgetsService->initWidgetRequest($request);
			$this->widgetsService->requestWidget($request);

			return ['result' => 'done', 'value' => $request->getResult()];
		} catch (Exception $e) {
			return ['result' => 'fail', 'message' => $e->getMessage()];
		}
	}


	/**
	 * @NoAdminRequired
	 * @NoSubAdminRequired
	 *
	 * @param string $json
	 *
	 * @return array
	 */
	public function pushWidget(string $json): array {
		$params = json_decode($json, true);
		$lastEventId = intval(MiscService::get($params, 'eventId', 0));

		if ($lastEventId === -1) {
			return $this->pushWidgetInit();
		}

		try {
			$data = $this->pushEventCheck($lastEventId);
		} catch (Exception $e) {
			return ['result' => 'fail', 'message' => $e->getMessage()];
		}

		return [
			'result'      => 'done',
			'lastEventId' => $lastEventId,
			'data'        => json_decode($data, true)
		];
	}


	/**
	 * @return array
	 */
	private function pushWidgetInit(): array {
		$lastEventId = $this->eventsService->getLastEventId();

		return [
			'result'      => 'done',
			'lastEventId' => $lastEventId
		];
	}


	/**
	 * @param int $lastEventId
	 *
	 * @return string
	 */
	private function pushEventCheck(int &$lastEventId): string {

		while (true) {
			sleep(10);

			$events = $this->eventsService->getEvents($this->userId, $lastEventId);
			if (sizeof($events) > 0) {
				$this->updateLastEventId($events, $lastEventId);
				$data = json_encode($events);

				return $data;
			}

			// we sent few data so we can detect if connection is lost
			echo ' ';
			ob_flush();
			flush();
		}

		return '';
	}


	/**
	 * @param IWidgetEvent[] $events
	 * @param int $lastEventId
	 */
	private function updateLastEventId(array $events, int &$lastEventId) {
		foreach ($events as $event) {
			if ($event->getId() > $lastEventId) {
				$lastEventId = $event->getId();
			}
		}
	}
}