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

namespace Markocupic\ContaoFrontendUserNotification\Tests\Controller;

use Contao\FrontendUser;
use Contao\TestCase\ContaoTestCase;
use Doctrine\DBAL\Connection;
use Markocupic\ContaoFrontendUserNotification\Controller\FrontendNotificationController;
use Markocupic\ContaoFrontendUserNotification\Model\FrontendUserNotificationModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class FrontendNotificationControllerTest extends ContaoTestCase
{
    public function testTagsOwnNotificationsAsRead(): void
    {
        $notification = $this->mockClassWithProperties(FrontendUserNotificationModel::class, ['id' => 7, 'user' => 42, 'isRead' => false]);
        $notification->expects($this->once())->method('save');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->once())->method('dispatch')->willReturnArgument(0);

        $response = $this->createController($notification, $eventDispatcher)->tagAsRead(new Request(), 7);

        $this->assertSame(['status' => 'success'], json_decode((string) $response->getContent(), true));
        $this->assertTrue($notification->isRead);
    }

    public function testDoesNotTagNotificationsOfOtherUsersAsRead(): void
    {
        $notification = $this->mockClassWithProperties(FrontendUserNotificationModel::class, ['id' => 7, 'user' => 99, 'isRead' => false]);
        $notification->expects($this->never())->method('save');

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects($this->never())->method('dispatch');

        $response = $this->createController($notification, $eventDispatcher)->tagAsRead(new Request(), 7);

        $this->assertSame(['status' => 'error'], json_decode((string) $response->getContent(), true));
        $this->assertFalse($notification->isRead);
    }

    public function testRequiresAFrontendUser(): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn(null);

        $controller = new FrontendNotificationController($this->mockContaoFramework(), $this->createMock(Connection::class), $this->createMock(EventDispatcherInterface::class), $security);
        $controller->setContainer(new Container());

        $response = $controller->tagAsRead(new Request(), 7);

        $this->assertSame(['status' => 'not_logged_in'], json_decode((string) $response->getContent(), true));
    }

    private function createController(FrontendUserNotificationModel $notification, EventDispatcherInterface $eventDispatcher): FrontendNotificationController
    {
        $adapter = $this->mockAdapter(['findById']);
        $adapter->method('findById')->with(7)->willReturn($notification);

        $user = $this->mockClassWithProperties(FrontendUser::class, ['id' => 42]);

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $controller = new FrontendNotificationController(
            $this->mockContaoFramework([FrontendUserNotificationModel::class => $adapter]),
            $this->createMock(Connection::class),
            $eventDispatcher,
            $security,
        );

        $controller->setContainer(new Container());

        return $controller;
    }
}
