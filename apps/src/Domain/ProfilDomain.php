<?php

namespace Labstag\Domain;

use Labstag\Entity\Profil;
use Labstag\Form\Admin\ProfilType;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfilDomain extends DomainLib
{
    public function __construct(
        protected UserRequestHandler $userRequestHandler,
        protected UserRepository $userRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Profil::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->userRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->userRequestHandler;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_profil' => $this->translator->trans('profil.title', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
