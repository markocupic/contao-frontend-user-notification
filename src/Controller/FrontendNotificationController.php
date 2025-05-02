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

namespace Markocupic\ContaoFrontendUserNotification\Controller;

use Contao\Config;
use Contao\Date;
use Contao\FrontendUser;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Markocupic\ContaoFrontendUserNotification\Event\TagNotificationAsReadEvent;
use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FrontendNotificationController extends AbstractController
{
    public function __construct(
        private readonly Connection $connection,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly Security $security,
    ) {
    }

    #[Route('/_frontend_user_notification/get', name: self::class.'_GET', defaults: ['_scope' => 'frontend', '_token_check' => false])]
    public function get(): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user instanceof FrontendUser) {
            return $this->json(['status' => 'not_logged_in']);
        }

        $rows = $this->connection->fetchAllAssociative(
            'SELECT * FROM tl_frontend_user_notification WHERE user = ? AND isRead = ? AND (endOfLifeTstamp = 0 OR endOfLifeTstamp > ?) ORDER BY id',
            [$user->id, 0, time()]
        );

        if (empty($rows)) {
            $json = [
                'status' => 'no_data_available',
            ];

            return $this->json($json);
        }

        $data = [];

        foreach ($rows as $row) {
            $row['dateAddedFormattedDate'] = Date::parse(Config::get('dateFormat'), $row['dateAdded']);
            $row['dateAddedFormattedDatim'] = Date::parse(Config::get('datimFormat'), $row['dateAdded']);
            $row = array_map(static fn ($varValue) => \is_string($varValue) ? mb_convert_encoding($varValue, 'UTF-8', 'UTF-8') : $varValue, $row);
            $row = array_map(static fn ($varValue) => \is_string($varValue) ? StringUtil::revertInputEncoding($varValue) : $varValue, $row);

            $data[] = $row;
        }

        if (empty($data)) {
            $json = [
                'status' => 'no_data_available',
            ];

            return $this->json($json);
        }

        $json = [
            'status' => 'success',
            'data' => $data,
        ];

        return $this->json($json);
    }

    #[Route('/_frontend_user_notification/tag_as_read/{id}', name: self::class.'_TAG_AS_READ', defaults: ['_scope' => 'frontend', '_token_check' => false])]
    public function tagAsRead(Request $request, int $id): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user instanceof FrontendUser) {
            return $this->json(['status' => 'not_logged_in']);
        }

        $model = FrontendUserNotificationModel::findByPk($id);

        if (null !== $model && !$model->isRead) {
            $model->isRead = true;
            $model->isReadTstamp = time();
            $model->tstamp = time();
            $model->save();

            $json = [
                'status' => 'success',
            ];

            $event = new TagNotificationAsReadEvent($model);
            $this->eventDispatcher->dispatch($event);

            return $this->json($json);
        }

        $json = [
            'status' => 'error',
        ];

        return $this->json($json);
    }
}
