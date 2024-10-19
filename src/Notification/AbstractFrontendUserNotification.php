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

namespace Markocupic\ContaoFrontendUserNotification\Notification;

use Contao\FrontendUser;
use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;
use Ramsey\Uuid\Uuid;

abstract class AbstractFrontendUserNotification implements FrontendUserNotificationInterface
{
    protected FrontendUserNotificationModel $model;

    public function __construct(FrontendUser $user, string $type, string $messageTitle, string $messageText, int $endOfLifeTstamp = 0)
    {
        $model = new FrontendUserNotificationModel();
        $model->user = $user->id;
        $model->type = $type;
        $model->dateAdded = time();
        $model->tstamp = time();
        $model->endOfLifeTstamp = $endOfLifeTstamp;
        $model->uuid = Uuid::uuid4()->toString();
        $model->messageTitle = $messageTitle;
        $model->messageText = $messageText;
        $model->save();

        $this->model = $model;
    }

    public function getModel(): FrontendUserNotificationModel
    {
        return $this->model;
    }
}
