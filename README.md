
![](https://raw.githubusercontent.com/nextcloud/dashboard/gridstack/screenshots/dashboard-grid.png)
### Quick guide on how to generate the app from git:

```
 $ git clone -b gridstack https://github.com/nextcloud/dashboard.git
 $ cd dashboard
 $ make composer npm
```


### How to create a Widget:

1. Generate an app
2. Create a PHP class that implement IDashboardWidget:
- [getId()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L48-L50) returns a unique ID of the widget
- [getName()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L56-L58) returns the name of the widget
- [getDescription()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L64-L66) returns a description of the widget
- [getTemplate()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/DiskSpaceWidget.php#L73-L82) returns information about the template to load and css/js:
- [widgetSetup](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L87-L107) returns optional information like size of the widget, additional menu entries and background jobs:
- [loadWidget($config)](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L113-L122) is called on external request (cf. requestWidget()). `$config` is an array that contains the current setup of the widget
- [requestWidget(WidgetRequest $request)](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L128-L132) is called after the loadWidget() after a [new.requestWidget(object, callback)](https://github.com/nextcloud/dashboard/blob/08c0850b5f586110264ac6f90e7f7e94ec070e4e/js/widgets/fortunes.js#L43-L50) from JavaScript


3. Add to appinfo/info.xml:

```
	<dashboard>
		<widget>OCA\YourApp\Widgets\MyFirstWidget</widget>
		<widget>OCA\YourApp\Widgets\AnOtherWidget</widget>
	</dashboard>
```

