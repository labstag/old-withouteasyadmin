<?php

namespace Labstag\Twig;

use Labstag\Entity\Attachment;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\ExtensionLib;
use Labstag\Repository\AttachmentRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LabstagExtension extends ExtensionLib
{
    public function classEntity(object $entity): string
    {
        $path = explode('\\', $entity::class);

        return strtolower(array_pop($path));
    }

    public function formClass(mixed $class): string
    {
        $data = $this->getformClassData($class);

        return $data['view'];
    }

    public function formPrototype(array $blockPrefixes): string
    {
        $data = $this->formPrototypeData($blockPrefixes);

        return $data['view'];
    }

    public function getAttachment(?EntityInterface $entity): ?Attachment
    {
        if (is_null($entity)) {
            return null;
        }

        $id = $entity->getId();
        /** @var AttachmentRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Attachment::class);
        $attachment    = $repositoryLib->findOneBy(['id' => $id]);
        if (is_null($attachment)) {
            return null;
        }

        /** @var Attachment $attachment */
        $name = (string) $attachment->getName();
        if (!is_file($name)) {
            return null;
        }

        return $attachment;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('attachment', [$this, 'getAttachment']),
            new TwigFilter('class_entity', [$this, 'classEntity']),
            new TwigFilter('formClass', [$this, 'formClass']),
            new TwigFilter('formPrototype', [$this, 'formPrototype']),
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('attachment', [$this, 'getAttachment']),
            new TwigFunction('imagefilter', [$this, 'imagefilter']),
            new TwigFunction('verifPhone', [$this, 'verifPhone']),
        ];
    }

    public function imagefilter(
        string $path,
        string $filter,
        array $config = [],
        ?string $resolver = null,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string
    {
        $url = $this->cacheManager->getBrowserPath(
            (string) parse_url($path, PHP_URL_PATH),
            $filter,
            $config,
            $resolver,
            $referenceType
        );

        return (string) parse_url($url, PHP_URL_PATH);
    }

    public function verifPhone(string $country, string $phone): bool
    {
        $verif = $this->phoneService->verif($phone, $country);

        return array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
    }
}
