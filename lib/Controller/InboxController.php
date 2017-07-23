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

use OCA\Dashboard\Service\DashboardService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

/**
 * Description of InboxController
 */
class InboxController extends Controller {

	/** @var DashboardService */
	private $dashboardService;

	/**
	 * InboxController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param DashboardService $dashboardService
	 */
	public function __construct($appName, IRequest $request, DashboardService $dashboardService) {
		parent::__construct($appName, $request);
		$this->dashboardService = $dashboardService;
	}

	/**
	 * load inbox
	 *
	 * @return DataResponse
	 * @NoAdminRequired
	 */
	public function index() {
		$data = [
			'data' => [],
		];

		$remoteSystemName = $this->dashboardService->getUserValue('inbox_remote_system_name');
		$port = $this->dashboardService->getUserValue('inbox_port');
		$ssl = $this->dashboardService->getUserValue('inbox_ssl');
		$flags = $ssl ? '/ssl' : '';
		$username = $this->dashboardService->getUserValue('inbox_username');
		$password = $this->dashboardService->getUserValueWithCrypto('inbox_password');

		if (empty($remoteSystemName) || empty($port) || empty($username) || empty($password)) {
			$data['message'] = 'Settings invalid.';
		} elseif (!function_exists('imap_open')) {
			$data['message'] = 'Extension missing: imap';
		} else {
			$imap_stream =
				imap_open("{{$remoteSystemName}:{$port}{$flags}}INBOX", $username, $password);
			$end = imap_check($imap_stream)->Nmsgs;
			$start = max(1, $end - $this->dashboardService->getAppValue('mail_limit', 5) + 1);
			$sequence = "$start:$end";
			$inbox = imap_fetch_overview($imap_stream, $sequence);
			imap_close($imap_stream);
			$error = imap_last_error();
			if ($error !== false) {
				$data['message'] = $error;
			} else {
				foreach ($inbox as $mail) {
					$data['data'][] = [
						'from'    => stripslashes(imap_utf8($mail->from)),
						'subject' => imap_utf8($mail->subject),
						'udate'   => $mail->udate,
						'seen'    => $mail->seen,
					];
				}
			}
		}

		return new DataResponse($data);
	}
}