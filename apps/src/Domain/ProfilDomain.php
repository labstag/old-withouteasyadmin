<?php

namespace Labstag\Domain;

use Labstag\Entity\Profil;
use Labstag\Form\Admin\ProfilType;
use Labstag\Lib\DomainLib;
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

    public function getEntity()
    {
        return Profil::class;
    }

    public function getRepository()
    {
        return $this->userRepository;
    }

    public function getRequestHandler()
    {
        return $this->userRequestHandler;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_profil'   => $this->translator->trans('profil.title', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return ProfilType::class;
    }
}
