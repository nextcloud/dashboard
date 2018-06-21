
![](https://raw.githubusercontent.com/nextcloud/dashboard/gridstack/screenshots/dashboard-grid.png)
### Quick guide on how to generate the app from git:

```
 $ git clone -b gridstack https://github.com/nextcloud/dashboard.git
 $ cd dashboard
 $ make composer npm
```


### How to create a Widget:

- Generate an app
- Create a PHP class that implement IDashboardWidget 
- widgetSetup() must return an array that contain at least the _template_ key:

```
	public function widgetSetup() {
		return [
			'template' => [
				'app'  => 'your_app,
				'icon' => 'icon-yourapp',
				'name' => 'templateName'
			],
		];
	}
```

You can specify the default size of the widget and/or settings that can be edited by the user. (use **lib/Widgets/TestWidget.php** as example)

- Add to appinfo/info.xml:

```
	<dashboard>
		<widget>OCA\YourApp\Widgets\MyFirstWidget</widget>
		<widget>OCA\YourApp\Widgets\AnOtherWidget</widget>
	</dashboard>
```

