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

class FrontendUserNotificationFetcher
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly TokenChecker $tokenChecker,
    ) {
    }

    public function hasFrontendUserNotifications(string $type = ''): bool
    {
        return null !== $this->findUnreadNotifications($type);
    }

    public function getFrontendUserNotifications(string $type = '', bool $autoConfirm = false): Collection|null
    {
        $results = $this->findUnreadNotifications($type);

        if (null === $results) {
            return null;
        }

        if ($autoConfirm) {
            while ($results->next()) {
                $results->isRead = true;
                $results->isReadTstamp = time();
                $results->tstamp = time();
                $results->save();
            }

            $results->reset();
        }

        return $results;
    }

    /**
     * Finds the unread notifications of the logged in frontend user, that have not expired
     * yet (an "endOfLifeTstamp" of 0 means that the notification never expires).
     */
    private function findUnreadNotifications(string $type): Collection|null
    {
        $user = $this->getLoggedInFrontendUser();

        if (null === $user) {
            return null;
        }

        $t = 'tl_frontend_user_notification';

        $arrColumns = ["$t.user=?", "($t.endOfLifeTstamp=0 OR $t.endOfLifeTstamp>?)", "$t.isRead=?"];
        $args = [$user->id, time(), 0];

        if ('' !== $type) {
            $arrColumns[] = "$t.type=?";
            $args[] = $type;
        }

        return $this->framework->getAdapter(FrontendUserNotificationModel::class)->findBy($arrColumns, $args);
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
