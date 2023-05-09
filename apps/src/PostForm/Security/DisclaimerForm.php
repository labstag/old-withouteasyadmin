<?php

namespace Labstag\PostForm\Security;

use Labstag\Form\Security\DisclaimerType;
use Labstag\Interfaces\PostFormInterface;
use Labstag\Lib\PostFormLib;

class DisclaimerForm extends PostFormLib implements PostFormInterface
{
    public function execute(
        array $success,
        string $formName
    ): array
    {
        $success[$formName] = false;

        return $success;
    }

    public function getForm(): string
    {
        return DisclaimerType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('security-disclaimer.name', [], 'postform');
    }
}
