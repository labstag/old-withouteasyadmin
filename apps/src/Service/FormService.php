<?php

namespace Labstag\Service;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class FormService
{
    public function __construct(
        protected RewindableGenerator $formfront,
        protected RewindableGenerator $postform
    ) {
    }

    public function all(): RewindableGenerator
    {
        return $this->formfront;
    }

    public function get(string $name): mixed
    {
        $form = null;
        foreach ($this->formfront as $form) {
            if ($form->getName() == $name) {
                break;
            }
        }

        return $form;
    }

    public function test(): array
    {
        $success = [];
        foreach ($this->formfront as $form) {
            $success = $this->execute($form, $success, $form->getName());
        }

        return $success;
    }

    private function execute(
        AbstractTypeLib $typeLib,
        array $success,
        string $formName
    ): array {
        $formClass = $typeLib::class;
        foreach ($this->postform as $row) {
            if ($row->getForm() == $formClass) {
                $success = $row->execute($formClass, $success, $formName);

                break;
            }
        }

        return $success;
    }
}
