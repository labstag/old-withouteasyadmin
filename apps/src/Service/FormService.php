<?php

namespace Labstag\Service;

use Labstag\Interfaces\PostFormInterface;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class FormService
{
    public function __construct(
        protected RewindableGenerator $postform,
        protected RewindableGenerator $securityform,
        protected RewindableGenerator $frontform
    )
    {
    }

    public function execute(
        AbstractTypeLib $typeLib,
        array $success,
        string $formName
    ): array
    {
        $formClass = $typeLib::class;
        foreach ($this->postform as $row) {
            /** @var PostFormInterface $row */
            if ($row->getForm() == $formClass) {
                $success = $row->execute($success, $formName);

                break;
            }
        }

        return $success;
    }

    public function getForm()
    {
        $data = [];
        foreach ($this->postform as $row) {
            $name        = $row->getName();
            $formclass   = $row->getForm();
            $form        = $this->getFormByClass($formclass);
            $data[$name] = $form->getBlockPrefix();
        }

        ksort($data);

        return $data;
    }

    private function getFormByClass(string $class): ?AbstractTypeLib
    {
        $result = null;
        foreach ($this->getForms() as $form) {
            if ($form::class != $class) {
                continue;
            }

            $result = $form;

            break;
        }

        return $result;
    }

    private function getForms(): array
    {
        $data = [];
        foreach ($this->securityform as $form) {
            $data[] = $form;
        }

        foreach ($this->frontform as $form) {
            $data[] = $form;
        }

        return $data;
    }
}
