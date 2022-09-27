<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\Search\User\PhoneUserType as UserPhoneUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Search\User\PhoneUserSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/phone')]
class PhoneUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_phoneuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_phoneuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?PhoneUser $phoneUser
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($phoneUser) ? new PhoneUser() : $phoneUser
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_phoneuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_phoneuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/phone_user/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_phoneuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_phoneuser_preview', methods: ['GET'])]
    public function showOrPreview(PhoneUser $phoneUser): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $phoneUser,
            'admin/user/phone_user/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(PhoneUser::class);
    }

    protected function searchForm(): array
    {
        return [
            'form' => UserPhoneUserType::class,
            'data' => new PhoneUserSearch(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_index',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_index',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_edit',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_new',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_trash',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_preview',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_show',
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [
            ...$headers, ...
            [
                'admin_phoneuser' => $this->translator->trans('phoneuser.title', [], 'admin.header'),
            ],
        ];
    }
}
