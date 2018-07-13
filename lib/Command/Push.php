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

namespace OCA\Dashboard\Command;

use Exception;
use OC\Core\Command\Base;
use OCA\Dashboard\Service\EventsService;
use OCA\Dashboard\Service\MiscService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Push extends Base {

	/** @var EventsService */
	private $eventsService;

	/** @var MiscService */
	private $miscService;


	/**
	 * Index constructor.
	 *
	 * @param EventsService $eventsService
	 * @param MiscService $miscService
	 */
	public function __construct(EventsService $eventsService, MiscService $miscService) {
		parent::__construct();

		$this->eventsService = $eventsService;
		$this->miscService = $miscService;
	}


	/**
	 *
	 */
	protected function configure() {
		parent::configure();
		$this->setName('dashboard:push')
			 ->setDescription('Push an event manually')
			 ->addArgument('widget', InputArgument::REQUIRED, 'widgetId')
			 ->addArgument('user', InputArgument::REQUIRED, 'userId')
			 ->addArgument('json', InputArgument::REQUIRED, 'payload');
	}


	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @throws Exception
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

		$widget = $input->getArgument('widget');
		$user = $input->getArgument('user');
		$payload = json_decode($input->getArgument('json'), true);

		if (!is_array($payload)) {
			throw new Exception('payload must be a valid JSON');
		}

		if ($user === 'global') {
			$this->eventsService->createGlobalEvent($widget, $payload);
		} else {
			$this->eventsService->createUserEvent($widget, $user, $payload);
		}
		$output->writeln('event created');
	}


}



