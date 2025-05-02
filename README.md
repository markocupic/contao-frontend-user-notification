![Alt text](docs/logo.png?raw=true "logo")


# Frontend User Notification Extension for the Contao CMS
This Contao extension can be used to display notifications to logged-in users in the Contao frontend.

![Alt text](docs/frontend.png?raw=true "frontend")

### All you have to do is...
Embed a frontend module of the type `Member Notifications` in your Contao layout.


You can generate the message in the backend or have it generated programmatically.

```php
// Add the notification programmatically
$type = 'happy-birthday-msg';

/** @var \Contao\FrontendUser $user */
$user = $this->getUser();  // Select a user who should have a notification displayed in the frontend.

$messageTitle = 'Happy  Birthday'
$messageText = 'bla, bla, bla';
$endOfLifeTimeStamp = time() + 7*24*3600; // 1 week

new Markocupic\ContaoFrontendUserNotification\Notification\DefaultFrontendUserNotification($user, $type, $messageTitle, $messageText, $endOfLifeTimeStamp)
```

### Dependencies
To use Bootstrap toast for the notifications you have to embed [Bootstrap](https://getbootstrap.com/docs/5.3/components/toasts/) manually.
