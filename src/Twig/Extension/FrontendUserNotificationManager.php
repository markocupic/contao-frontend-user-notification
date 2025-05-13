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

namespace Markocupic\ContaoFrontendUserNotification\Twig\Extension;

use Contao\Model\Collection;
use Markocupic\ContaoFrontendUserNotification\FrontendUserNotificationFetcher;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FrontendUserNotificationManager extends AbstractExtension
{
    public function __construct(
        private readonly FrontendUserNotificationFetcher $frontendUserNotificationFetcher,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_frontend_user_notifications', [$this, 'hasFrontendUserNotifications']),
            new TwigFunction('get_frontend_user_notifications', [$this, 'getFrontendUserNotifications']),
        ];
    }

    public function hasFrontendUserNotifications(string $type = ''): bool
    {
        return $this->frontendUserNotificationFetcher->hasFrontendUserNotifications($type);
    }

    public function getFrontendUserNotifications(string $type = '', bool $autoConfirm = false): Collection|null
    {
        return $this->frontendUserNotificationFetcher->getFrontendUserNotifications($type, $autoConfirm);
    }
}
