
## Installation

As long as the changes made by this fork are not merged into
nextcloud/dashboard master, follow these steps to install this fork.

### Preparation

Disable and remove the previous version of the Dashboard App thru the
admin backend.


### Installation per git clone

Open a terminial and clone the fork with following commands into your
nextcloud/apps/ folder:
```
cd /var/www/[path to your webroot]/nextcloud/apps/
```
```
git clone https://github.com/tuxedocomputers/dashboard.git
```

Open a terminal and change the ownership of the whole dashboard directory to
your webservice user and group (usually `www-data` or `apache`):
```
chown -R www-data:www-data /var/www/[path to your webroot]/nextcloud/apps/dashboard
```

Finally, enable the Dashboard App through the admin backend.


### Installation per archive download

Download the archive from:
[https://github.com/tuxedocomputers/dashboard/archive/master.zip](https://github.com/tuxedocomputers/dashboard/archive/master.zip)

Open a terminal, unzip the archive into your nextcloud/apps/ folder and
rename the dashboard-master folder to dashboard: 
```
cd [path to downloaded archive]
```
```
unzip master.zip -d /var/www/[path to your webroot]/nextcloud/apps/
```
```
mv /var/www/[path to your webroot]/nextcloud/apps/dashboard-master /var/www/[path to your webroot]/nextcloud/apps/dashboard
```

Open a terminal and change the ownership of the whole dashboard directory to
your webservice user and group (usually `www-data` or `apache`):
```
chown -R www-data:www-data /var/www/[path to your webroot]/nextcloud/apps/dashboard
```

Finally, enable the Dashboard App through the admin backend.


## Notes

- to be able to create/edit/delete announcements, the user have to be a member
  of user-group 'News'

- this user-group is hardcoded in following files:
  `./dashboard/lib/Controller/AnnouncementController.php`
  and
  `./dashboard/lib/Controller/PageController.php`

- an disabled dashboard section in the additional admin settings should have a
  unique placement number, even if it should not displayed
  (don't use the same placement number several times)


## Todo

- make announcement group configurable

- check inbox implementation
