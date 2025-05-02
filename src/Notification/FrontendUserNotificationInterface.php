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

namespace Markocupic\ContaoFrontendUserNotification\Notification;

use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('markocupic_sac_event_tool.frontend_user_notification')]
interface FrontendUserNotificationInterface
{
    public function getModel(): FrontendUserNotificationModel;
}
