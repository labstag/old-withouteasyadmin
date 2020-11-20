<?php

namespace Labstag\Service;

use Labstag\Queue\EnqueueMethod;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerService
{

    private Environment $twig;

    private EnqueueMethod $enqueue;

    private MailerInterface $mailer;

    public function __construct(
        Environment $twig,
        EnqueueMethod $enqueue,
        MailerInterface $mailer
    )
    {
        $this->twig    = $twig;
        $this->enqueue = $enqueue;
        $this->mailer  = $mailer;
    }

    public function createEmail(array $template, array $data = []): Email
    {
        $email = new Email();
        if (isset($template['html'])) {
            $this->twig->addGlobal('format', 'html');
            $html = $this->twig->render(
                $template['html'],
                array_merge(
                    $data,
                    ['layout' => 'mails/base.html.twig']
                )
            );
            $email->html($html);
        }

        if (isset($template['txt'])) {
            $this->twig->addGlobal('format', 'text');
            $text = $this->twig->render(
                $template['txt'],
                array_merge(
                    $data,
                    ['layout' => 'mails/base.text.twig']
                )
            );
            $email->text($text);
        }

        return $email->from('noreply@labstag.lxc');
    }

    public function send(Email $email): void
    {
        $this->enqueue->enqueue(
            MailerInterface::class,
            'send',
            [$email]
        );
    }

    public function sendNow(Email $email): void
    {
        $this->mailer->send($email);
    }
}
