<?php

declare(strict_types=1);

/*
 * This file is part of Contao Frontend User Notification.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-frontend-user-notification
 */

use Markocupic\ContaoFrontendUserNotification\Controller\FrontendModule\FontendUserNotificationController;

/*
 * Backend modules
 */
$GLOBALS['TL_LANG']['MOD']['notification'] = 'Notifications';
$GLOBALS['TL_LANG']['MOD']['frontend_user_notification'] = [
    'Member Notifications',
    'Manage Member Notifications',
];

/*
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['notification'] = 'Notifications';
$GLOBALS['TL_LANG']['FMD'][FontendUserNotificationController::TYPE] = [
    'Member Notifications',
    'Show Member Notifications',
];
