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

namespace OCA\Dashboard\Controller;

use OCA\Dashboard\Db\Announcement;
use OCA\Dashboard\Db\AnnouncementMapper;
use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;

/**
 * Description of AnnouncementController
 */
class AnnouncementController extends Controller {

	const TITLE = 'title';
	const CONTENT = 'content';
	const EXPIRATION = 'expiration';

	/** @var AnnouncementMapper */
	private $announcementMapper;

	/** @var DashboardService */
	private $dashboardService;

	/** @var string */
	private $userId;

	/**
	 * AnnouncementController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param AnnouncementMapper $announcementMapper
	 * @param DashboardService $dashboardService
	 * @param string $userId
	 */
	public function __construct(
		$appName, IRequest $request, AnnouncementMapper $announcementMapper,
		DashboardService $dashboardService, $userId
	) {
		parent::__construct($appName, $request);
		$this->announcementMapper = $announcementMapper;
		$this->dashboardService = $dashboardService;
		$this->userId = $userId;
	}

	/**
	 * create Announcement
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 */
	public function create() {
		$data = [];
		// todo: group should be configurable
		$canCreateAnnouncements = $this->dashboardService->isInGroup('News');

		if (!$canCreateAnnouncements) {
			return new DataResponse($data, Http::STATUS_FORBIDDEN);
		}

		$definition = [
			static::TITLE      => [
				'filter' => FILTER_SANITIZE_STRING,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::CONTENT    => [
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::EXPIRATION => [
				'filter'  => FILTER_VALIDATE_REGEXP,
				'flags'   => FILTER_NULL_ON_FAILURE,
				'options' => [
					'regexp' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'
				],
			],
		];
		$input = filter_input_array(INPUT_POST, $definition);

		foreach ($input as $key => $value) {
			if (empty($value)) {
				return new DataResponse($data, Http::STATUS_BAD_REQUEST);
			}
		}

		$announcement = new Announcement();
		$announcement->setTitle($input[static::TITLE]);
		$announcement->setContent($input[static::CONTENT]);
		$announcement->setExpiration($input[static::EXPIRATION]);
		$announcement->setUserId($this->userId);
		$announcement->setCreatedAt(date('Y-m-d H:i:s'));
		$this->announcementMapper->insert($announcement);

		return new DataResponse($data, Http::STATUS_CREATED);
	}

	/**
	 * load update Announcement
	 *
	 * @param $id
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 */
	public function edit($id) {
		$data = [];

		$canEditAnnouncements = $this->dashboardService->isInGroup('News');
		if (!$canEditAnnouncements) {
			return new DataResponse($data, Http::STATUS_FORBIDDEN);
		}

		$entity = $this->announcementMapper->findOne(intval($id));
		if ($entity == null) {
			return new DataResponse($data, HTTP::STATUS_NOT_FOUND);
		}

		return new DataResponse($entity);
	}

	/**
	 * update Announcement
	 *
	 * @param $id
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 */
	public function updateEdit($id) {
		$data = [];

		$canEditAnnouncements = $this->dashboardService->isInGroup('News');

		if (!$canEditAnnouncements) {
			return new DataResponse($data, Http::STATUS_FORBIDDEN);
		}

		$definition = [
			static::TITLE      => [
				'filter' => FILTER_SANITIZE_STRING,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::CONTENT    => [
				'filter' => FILTER_UNSAFE_RAW,
				'flags'  => FILTER_NULL_ON_FAILURE,
			],
			static::EXPIRATION => [
				'filter'  => FILTER_VALIDATE_REGEXP,
				'flags'   => FILTER_NULL_ON_FAILURE,
				'options' => [
					'regexp' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/'
				],
			],
		];
		$input = filter_input_array(INPUT_POST, $definition);

		foreach ($input as $key => $value) {
			if (empty($value)) {
				return new DataResponse($data, Http::STATUS_BAD_REQUEST);
			}
		}

		$entity = $this->announcementMapper->findOne(intval($id));
		$entity->setTitle($input[static::TITLE]);
		$entity->setContent($input[static::CONTENT]);
		$entity->setExpiration($input[static::EXPIRATION]);
		$entity->setUserId($this->userId);
		$entity->setId(intval($id));
		$erg = $this->announcementMapper->update($entity);

		return new DataResponse($data, Http::STATUS_CREATED);
	}

	/**
	 * delete Announcement
	 *
	 * @param $id
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 */
	public function destroy($id) {
		$data = [];

		$canDeleteAnnouncements = $this->dashboardService->isInGroup('News');

		if (!$canDeleteAnnouncements) {
			return new DataResponse($data, Http::STATUS_FORBIDDEN);
		}

		$entity = $this->announcementMapper->findOne(intval($id));
		if ($entity == null) {
			return new DataResponse($data, HTTP::STATUS_NOT_FOUND);
		}
		$this->announcementMapper->delete($entity);

		return new DataResponse($data, HTTP::STATUS_NO_CONTENT);
	}


	/**
	 * load Announcements
	 *
	 * @return TemplateResponse
	 * @NoAdminRequired
	 */
	public function index() {
		$canDeleteAnnouncements = $this->dashboardService->isInGroup('News');
		$canEditAnnouncements = $this->dashboardService->isInGroup('News');

		$limit = 5;
		$announcements = $this->announcementMapper->findAll($limit);

		$params = [
			'announcements'            => $announcements,
			'can_delete_announcements' => $canDeleteAnnouncements,
			'can_edit_announcements'   => $canEditAnnouncements
		];

		return new TemplateResponse($this->appName, 'announcements', $params, 'blank');
	}
}