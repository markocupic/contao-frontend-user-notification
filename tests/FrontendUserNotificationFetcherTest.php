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

namespace Markocupic\ContaoFrontendUserNotification\Tests;

use Contao\CoreBundle\Security\Authentication\Token\TokenChecker;
use Contao\MemberModel;
use Contao\Model\Collection;
use Contao\TestCase\ContaoTestCase;
use Markocupic\ContaoFrontendUserNotification\FrontendUserNotificationFetcher;
use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;

class FrontendUserNotificationFetcherTest extends ContaoTestCase
{
    public function testReturnsNothingWithoutFrontendUser(): void
    {
        $tokenChecker = $this->createMock(TokenChecker::class);
        $tokenChecker->method('hasFrontendUser')->willReturn(false);

        $notificationAdapter = $this->mockAdapter(['findBy']);
        $notificationAdapter->expects($this->never())->method('findBy');

        $fetcher = new FrontendUserNotificationFetcher($this->mockContaoFramework([FrontendUserNotificationModel::class => $notificationAdapter]), $tokenChecker);

        $this->assertFalse($fetcher->hasFrontendUserNotifications());
        $this->assertNull($fetcher->getFrontendUserNotifications());
    }

    public function testIncludesNotificationsWithoutEndOfLife(): void
    {
        $notificationAdapter = $this->mockAdapter(['findBy']);
        $notificationAdapter
            ->expects($this->once())
            ->method('findBy')
            ->with(
                [
                    'tl_frontend_user_notification.user=?',
                    '(tl_frontend_user_notification.endOfLifeTstamp=0 OR tl_frontend_user_notification.endOfLifeTstamp>?)',
                    'tl_frontend_user_notification.isRead=?',
                    'tl_frontend_user_notification.type=?',
                ],
                $this->callback(static fn (array $args): bool => 42 === $args[0] && \is_int($args[1]) && 0 === $args[2] && 'news' === $args[3]),
            )
            ->willReturn($this->createMock(Collection::class))
        ;

        $fetcher = $this->createFetcher($notificationAdapter);

        $this->assertTrue($fetcher->hasFrontendUserNotifications('news'));
    }

    public function testTagsTheNotificationsAsReadIfAutoConfirmIsEnabled(): void
    {
        $notification = $this->mockClassWithProperties(FrontendUserNotificationModel::class, ['isRead' => false]);
        $notification->expects($this->once())->method('save');

        $collection = new Collection([$notification], 'tl_frontend_user_notification');

        $notificationAdapter = $this->mockAdapter(['findBy']);
        $notificationAdapter->method('findBy')->willReturn($collection);

        $result = $this->createFetcher($notificationAdapter)->getFrontendUserNotifications('', true);

        $this->assertSame($collection, $result);
        $this->assertTrue($notification->isRead);
        $this->assertIsInt($notification->isReadTstamp);
    }

    private function createFetcher(object $notificationAdapter): FrontendUserNotificationFetcher
    {
        $tokenChecker = $this->createMock(TokenChecker::class);
        $tokenChecker->method('hasFrontendUser')->willReturn(true);
        $tokenChecker->method('getFrontendUsername')->willReturn('jdoe');

        $member = $this->mockClassWithProperties(MemberModel::class, ['id' => 42]);

        $memberAdapter = $this->mockAdapter(['findByUsername']);
        $memberAdapter->method('findByUsername')->with('jdoe')->willReturn($member);

        $framework = $this->mockContaoFramework([
            MemberModel::class => $memberAdapter,
            FrontendUserNotificationModel::class => $notificationAdapter,
        ]);

        return new FrontendUserNotificationFetcher($framework, $tokenChecker);
    }
}
