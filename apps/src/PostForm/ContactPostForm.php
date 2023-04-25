<?php

namespace Labstag\PostForm;

use Labstag\Form\Front\ContactType;
use Labstag\Interfaces\PostFormInterface;
use Labstag\Lib\PostFormLib;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mime\Email;

class ContactPostForm extends PostFormLib implements PostFormInterface
{
    public function execute(
        array $success,
        string $formName
    ): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $form    = $this->formFactory->create(ContactType::class);
        $form->handleRequest($request);
        $success[$formName] = false;
        $toArray            = $this->getToEmails();
        if (!is_array($toArray)) {
            return $success;
        }

        if (0 == (is_countable($toArray) ? count($toArray) : 0)) {
            return $success;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $success = $this->setMail($success, $form, $toArray, $formName);
        }

        return $success;
    }

    public function getForm(): string
    {
        return ContactType::class;
    }

    private function setMail(
        array $success,
        FormInterface $form,
        array $toArray,
        string $formName
    ): array
    {
        $body = [
            'name'      => '',
            'firstname' => '',
            'mail'      => '',
            'phone'     => '',
            'message'   => '',
        ];

        foreach (array_keys($body) as $key) {
            if ($form->has($key)) {
                $value = $form->get($key)->getData();
                if (is_string($value)) {
                    $body[$key] = trim($value);
                }
            }
        }

        $email = new Email();
        foreach ($toArray as $row) {
            $email->addTo($row);
        }

        $email->from($this->getEmailFrom());
        $email->subject('Contact depuis le site " '.$this->getTitle());
        $email->html(
            $this->renderView(
                'email/contact.html.twig',
                $body
            )
        );

        $this->mailer->send($email);
        //Flashbag message envoyé
        $success[$formName] = true;

        return $success;
    }
}
