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

namespace Markocupic\ContaoFrontendUserNotification\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\FrontendUser;
use Contao\MemberModel;
use Contao\ModuleModel;
use Contao\PageModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(category: 'notification')]
class FrontendUserNotificationController extends AbstractFrontendModuleController
{
    public const TYPE = 'frontend_user_notification';

    protected PageModel|null $page;

    public function __construct(
        private readonly Security $security,
        private readonly ScopeMatcher $scopeMatcher,
    ) {
    }

    public function __invoke(Request $request, ModuleModel $model, string $section, array|null $classes = null, PageModel|null $page = null): Response
    {
        if ($this->scopeMatcher->isFrontendRequest($request) && !$this->security->getUser() instanceof FrontendUser) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        return parent::__invoke($request, $model, $section, $classes);
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $user = $this->security->getUser();
        $template->set('id', $model->id);
        $template->set('user', MemberModel::findByPk($user->id)->row());

        return $template->getResponse();
    }
}
