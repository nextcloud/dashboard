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

namespace OCA\Dashboard\Model;


use OC\Dashboard\Model\WidgetSetup;
use OC\Dashboard\Model\WidgetTemplate;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Dashboard\IDashboardWidget;
use OCP\Dashboard\Model\IWidgetConfig;

class WidgetFrame implements \JsonSerializable {

	/** @var string */
	private $appId;

	/** @var IDashboardWidget */
	private $widget;

	/** @var WidgetSetup */
	private $setup;

	/** @var WidgetTemplate */
	private $template;

	/** @var WidgetConfig */
	private $config;


	/**
	 * WidgetFrame constructor.
	 *
	 * @param string $appId
	 * @param IDashboardWidget $widget
	 */
	public function __construct(string $appId, IDashboardWidget $widget) {
		$this->appId = $appId;
		$this->widget = $widget;
	}


	/**
	 * @return string
	 */
	public function getAppId(): string {
		return $this->appId;
	}

	/**
	 * @param string $appId
	 *
	 * @return WidgetFrame
	 */
	public function setAppId(string $appId): WidgetFrame {
		$this->appId = $appId;

		return $this;
	}


	/**
	 * @return IDashboardWidget
	 */
	public function getWidget(): IDashboardWidget {
		return $this->widget;
	}

	/**
	 * @param IDashboardWidget $widget
	 *
	 * @return $this
	 */
	public function setWidget(IDashboardWidget $widget): WidgetFrame {
		$this->widget = $widget;

		return $this;
	}


	/**
	 * @return WidgetSetup
	 */
	public function getSetup(): WidgetSetup {
		return $this->setup;
	}

	/**
	 * @param WidgetSetup $setup
	 *
	 * @return WidgetFrame
	 */
	public function setSetup(WidgetSetup $setup): WidgetFrame {
		$this->setup = $setup;

		return $this;
	}


	/**
	 * @return WidgetTemplate
	 */
	public function getTemplate(): WidgetTemplate {
		return $this->template;
	}

	/**
	 * @param WidgetTemplate $template
	 *
	 * @return WidgetFrame
	 */
	public function setTemplate(WidgetTemplate $template): WidgetFrame {
		$this->template = $template;

		return $this;
	}


	/**
	 * @return IWidgetConfig
	 */
	public function getConfig(): IWidgetConfig {
		return $this->config;
	}

	/**
	 * @param IWidgetConfig $config
	 *
	 * @return $this
	 */
	public function setConfig(IWidgetConfig $config): WidgetFrame {
		$this->config = $config;

		return $this;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		$widget = $this->getWidget();

		$template = $this->getTemplate();
		$html = new TemplateResponse($this->getAppId(), $template->getContent(), [], 'blank');

		return [
			'widget'   => [
				'id'          => $widget->getId(),
				'name'        => $widget->getName(),
				'description' => $widget->getDescription()
			],
			'template' => $template,
			'setup'    => $this->getSetup(),
			'html'     => $html->render(),
			'config'   => $this->getConfig()
		];
	}
}
