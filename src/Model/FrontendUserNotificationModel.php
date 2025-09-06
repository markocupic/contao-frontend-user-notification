<?php

declare(strict_types=1);

/*
 * This file is part of Contao Frontend User Notification.
 *
 * (c) Marko Cupic <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-frontend-user-notification
 */

namespace Markocupic\ContaoFrontendUserNotification\Model;

use Contao\Model;

class FrontendUserNotificationModel extends Model
{
    protected static $strTable = 'tl_frontend_user_notification';
}
