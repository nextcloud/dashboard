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
		$valCert = $this->dashboardService->getUserValue('inbox_validate_cert');
		$flags = $ssl ? '/ssl' : '';
		$flags .= $valCert ? '/validate-cert' : '/novalidate-cert';
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
