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

use Markocupic\ContaoFrontendUserNotification\Controller\FrontendModule\FrontendUserNotificationController;

/**
 * Frontend modules
 */
$GLOBALS['TL_DCA']['tl_module']['palettes'][FrontendUserNotificationController::TYPE] = '{title_legend},name,headline,type;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
