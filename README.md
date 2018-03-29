
## Installation

run `composer install` to get 3rd party scripts


## Notes

- to be able to create/edit/delete announcements, the user have to be a member
  of user-group 'News'

- this user-group is hardcoded in following files:
  `./dashboard/lib/Controller/AnnouncementController.php`
  and
  `./dashboard/lib/Controller/PageController.php`

- an disabled dashboard section in the advanced admin settings should have a
  unique placement number, even if it should not displayed
  (don't use a placement number several times)


## Todo

- make announcement group configurable

- check inbox implementation
