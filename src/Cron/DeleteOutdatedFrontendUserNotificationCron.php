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

namespace Markocupic\ContaoFrontendUserNotification\Cron;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Model\Collection;
use Markocupic\ContaoFrontendUserNotification\Event\DeleteOutdatedFrontendUserNotificationEvent;
use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsCronJob('daily')]
readonly class DeleteOutdatedFrontendUserNotificationCron
{
    public function __construct(
        private ContaoFramework $framework,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(): void
    {
        $this->framework->initialize();

        $modelCollection = $this->getOutdatedMessagesCollection();

        if (null === $modelCollection) {
            return;
        }

        while ($modelCollection->next()) {
            $model = $modelCollection->current();

            $event = new DeleteOutdatedFrontendUserNotificationEvent($model);
            $this->eventDispatcher->dispatch($event);

            if ($event->shouldDelete()) {
                $model->delete();
            }
        }
    }

    private function getOutdatedMessagesCollection(): Collection|null
    {
        $strTable = FrontendUserNotificationModel::getTable();

        return FrontendUserNotificationModel::findBy(
            [
                $strTable.'.endOfLifeTstamp != ?',
                $strTable.'.endOfLifeTstamp < ?',
            ],
            [
                0,
                time(),
            ]
        );
    }
}
