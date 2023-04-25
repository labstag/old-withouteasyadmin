<?php

namespace Labstag\Lib;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

abstract class PostFormLib
{
    public function __construct(
        protected MailerInterface $mailer,
        protected RequestStack $requestStack,
        protected FormFactoryInterface $formFactory,
        protected Environment $twigEnvironment
    )
    {
    }

    protected function getEmailFrom(): string
    {
        return 'test@test.local';
    }

    protected function getTitle(): string
    {
        return 'SITE NAME';
    }

    protected function getToEmails(): array
    {
        return [];
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        return $this->twigEnvironment->render($view, $parameters);
    }
}
