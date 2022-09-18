<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Form\Admin\Search\EditoType as SearchEditoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\Search\EditoSearch;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/edito')]
class EditoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_edito_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Edito $edito,
        EditoRequestHandler $editoRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $editoRequestHandler,
            EditoType::class,
            is_null($edito) ? new Edito() : $edito,
            'admin/edito/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_edito_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_edito_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Edito::class,
            'admin/edito/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_edito_new', methods: ['GET', 'POST'])]
    public function new(
        EditoRepository $editoRepository,
        EditoRequestHandler $editoRequestHandler,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();

        $edito = new Edito();
        $edito->setPublished(new DateTime());
        $edito->setTitle(Uuid::v1());
        $edito->setRefuser($user);

        $old = clone $edito;
        $editoRepository->add($edito);
        $editoRequestHandler->handle($old, $edito);

        return $this->redirectToRoute('admin_edito_edit', ['id' => $edito->getId()]);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_edito_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_edito_preview', methods: ['GET'])]
    public function showOrPreview(Edito $edito): Response
    {
        return $this->renderShowOrPreview(
            $edito,
            'admin/edito/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_edito_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_edito_index',
            'new'      => 'admin_edito_new',
            'preview'  => 'admin_edito_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_edito_show',
            'trash'    => 'admin_edito_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    /**
     * @return array<string, \EditoSearch>|array<string, class-string<\Labstag\Form\Admin\Search\EditoType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchEditoType::class,
            'data' => new EditoSearch(),
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
                    'title' => $this->translator->trans('edito.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_edito_index',
                ],
                [
                    'title' => $this->translator->trans('edito.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_edito_edit',
                ],
                [
                    'title' => $this->translator->trans('edito.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_edito_new',
                ],
                [
                    'title' => $this->translator->trans('edito.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_edito_trash',
                ],
                [
                    'title' => $this->translator->trans('edito.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_edito_preview',
                ],
                [
                    'title' => $this->translator->trans('edito.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_edito_show',
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
                'admin_edito' => $this->translator->trans('edito.title', [], 'admin.header'),
            ],
        ];
    }
}
