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

use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;

/*
 * Backend modules
 */
$GLOBALS['BE_MOD']['notification']['frontend_user_notification'] = [
    'tables' => ['tl_frontend_user_notification'],
];

/*
 * Models
 */
$GLOBALS['TL_MODELS']['tl_frontend_user_notification'] = FrontendUserNotificationModel::class;
