
![](https://raw.githubusercontent.com/nextcloud/dashboard/master/screenshots/dashboard-grid.png)
### Quick guide on how to build the app from git:

```
 $ git clone -b gridstack https://github.com/nextcloud/dashboard.git
 $ cd dashboard
 $ make npm
```


### How to create a Widget:

- Generate an app (see [app development documentation](https://docs.nextcloud.com/server/16/developer_manual/app/index.html) or use the [app genreator from the app store.)](https://apps.nextcloud.com/developer/apps/generate)
- Create a PHP class that implement IDashboardWidget:
1. [getId()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L48-L50) returns a unique ID of the widget
2. [getName()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L56-L58) returns the name of the widget
3. [getDescription()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L64-L66) returns a description of the widget
4. [getTemplate()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/DiskSpaceWidget.php#L73-L82) returns information about the template to load and css/js:
5. [widgetSetup()](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L87-L107) returns optional information like size of the widget, additional menu entries and background jobs:
6. [loadWidget($config)](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L113-L122) is called on external request (cf. requestWidget()). `$config` is an array that contains the current setup of the widget
7. [requestWidget(WidgetRequest $request)](https://github.com/nextcloud/dashboard/blob/a1d2f0d72d6d7a62e4309da7291bf215395ba7d7/lib/Widgets/FortunesWidget.php#L128-L132) is called after the loadWidget() after a [new.requestWidget(object, callback)](https://github.com/nextcloud/dashboard/blob/08c0850b5f586110264ac6f90e7f7e94ec070e4e/js/widgets/fortunes.js#L43-L50) from JavaScript  

- Add to appinfo/info.xml:

```
	<dashboard>
		<widget>OCA\YourApp\Widgets\MyFirstWidget</widget>
		<widget>OCA\YourApp\Widgets\AnOtherWidget</widget>
	</dashboard>
```

### Event & Push

You can (almost) instantly push payload from Nextcloud to your widget:
 
 - define the method to be called in the `widgetSetup()` array:

```
   'push'  =>  'your-javascript-function-to-call-on-event
```

 - call the API from your PHP:

```
OCA\Dashboard\Api\v1\Dashboard::createEvent('your_widget_id', 'user_id', payload_in_JSON);
```

 - the method set in `widgetSetup()['push']` will receive the payload.

_Note: you can manually generate events using the command line:_


>      ./occ dashboard:push widgetid userid payload

_You can, this way, modify the displayed fortune for any user:_

>     ./occ dashboard:push fortunes cult "{\"fortune\": \"foobar\"}"
