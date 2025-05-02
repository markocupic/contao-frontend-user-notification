<?php

declare(strict_types=1);

/*
 * This file is part of Contao Frontend User Notification.
 *
 * (c) Marko Cupic 2025 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-frontend-user-notification
 */

/*
 * Legends
 */
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['user_legend'] = 'Member';
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['details_legend'] = 'Details';
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['notification_legend'] = 'Advanced settings';

/*
 * Global operations
 */
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['new'] = [
    'New notification',
    'Create a new notification',
];

/*
 * Operations
 */
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['edit'] = 'Edit notification with ID: %s';
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['copy'] = 'Copy notification with ID: %s';
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['delete'] = 'Delete notification with ID: %s';
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['show'] = 'View notification with ID: %s';

/*
 * Fields
 */
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['user'] = [
    'Member',
    'Select a member for the notification',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['dateAdded'] = [
    'Creation time',
    'Enter the creation date and time',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['endOfLifeTstamp'] = [
    'Expiry time',
    'Enter the time from which the message can be deleted from the database.',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['type'] = [
    'Notification type',
    'Enter the notification type',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['uuid'] = [
    'UUID',
    'Enter the UUID',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['messageTitle'] = [
    'Title',
    'Enter a title or salutation',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['messageText'] = [
    'Notification',
    'Enter the notification',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['isRead'] = [
    'Read Yes/No',
    'Specify whether the notification has been read',
];
$GLOBALS['TL_LANG']['tl_frontend_user_notification']['isReadTstamp'] = [
    'Read on',
    'Specify the time at which the message was read',
];
