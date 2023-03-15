<?php

namespace Labstag\Service;

use Labstag\Interfaces\PostFormInterface;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class FormService
{
    public function __construct(
        protected RewindableGenerator $rewindableGenerator
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
        foreach ($this->rewindableGenerator as $row) {
            /** @var PostFormInterface $row */
            if ($row->getForm() == $formClass) {
                $success = $row->execute($success, $formName);

                break;
            }
        }

        return $success;
    }
}
