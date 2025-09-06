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

namespace Markocupic\ContaoFrontendUserNotification;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
use Contao\MemberModel;
use Contao\Model\Collection;
use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;
use Twig\Extension\AbstractExtension;

class FrontendUserNotificationFetcher extends AbstractExtension
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly TokenChecker $tokenChecker,
    ) {
    }

    public function hasFrontendUserNotifications(string $type = ''): bool
    {
        if (!$this->tokenChecker->hasFrontendUser()) {
            return false;
        }

        $user = $this->getLoggedInFrontendUser();
        $t = 'tl_frontend_user_notification';

        if ('' !== $type) {
            $arrColumns = ["$t.user=?", "$t.endOfLifeTstamp>?", "$t.type=?", "$t.isRead=?"];
            $args = [$user->id, time(), $type, 0];
        } else {
            $arrColumns = ["$t.user=?", "$t.endOfLifeTstamp>?", "$t.isRead=?"];
            $args = [$user->id, time(), 0];
        }

        $adapter = $this->framework->getAdapter(FrontendUserNotificationModel::class);

        $results = $adapter->findBy($arrColumns, $args);

        if (null !== $results) {
            return true;
        }

        return false;
    }

    public function getFrontendUserNotifications(string $type = '', bool $autoConfirm = false): Collection|null
    {
        if (!$this->hasFrontendUserNotifications($type)) {
            return null;
        }

        $user = $this->getLoggedInFrontendUser();
        $t = 'tl_frontend_user_notification';

        if ('' !== $type) {
            $arrColumns = ["$t.user=?", "$t.endOfLifeTstamp>?", "$t.type=?", "$t.isRead=?"];
            $args = [$user->id, time(), $type, 0];
        } else {
            $arrColumns = ["$t.user=?", "$t.endOfLifeTstamp>?", "$t.isRead=?"];
            $args = [$user->id, time(), 0];
        }

        $adapter = $this->framework->getAdapter(FrontendUserNotificationModel::class);

        $results = $adapter->findBy($arrColumns, $args);

        if (null === $results) {
            return null;
        }

        while ($results->next()) {
            if ($autoConfirm) {
                $results->isRead = 1;
                $results->save();
            }
        }

        $results->reset();

        return $results;
    }

    private function getLoggedInFrontendUser(): MemberModel|null
    {
        if ($this->tokenChecker->hasFrontendUser()) {
            $adapter = $this->framework->getAdapter(MemberModel::class);

            if (null !== ($model = $adapter->findByUsername($this->tokenChecker->getFrontendUsername()))) {
                return $model;
            }
        }

        return null;
    }
}
