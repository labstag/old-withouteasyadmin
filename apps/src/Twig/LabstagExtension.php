<?php

namespace Labstag\Twig;

use Labstag\Entity\Attachment;
use Labstag\Lib\ExtensionLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\Service\PhoneService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class LabstagExtension extends ExtensionLib
{
    /**
     * @var string
     */
    final public const FOLDER_ENTITY = 'Labstag\\Entity\\';

    public function __construct(
        protected Environment $twigEnvironment,
        protected PhoneService $phoneService,
        protected CacheManager $cacheManager,
        protected AttachmentRepository $attachmentRepository
    )
    {
        parent::__construct($twigEnvironment);
    }

    public function classEntity(object $entity): string
    {
        $class = substr(
            (string) $entity::class,
            strpos((string) $entity::class, self::FOLDER_ENTITY) + strlen(self::FOLDER_ENTITY)
        );

        return trim(strtolower($class));
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

    public function getAttachment(mixed $data): ?Attachment
    {
        if (is_null($data)) {
            return null;
        }

        $id         = $data->getId();
        $attachment = $this->attachmentRepository->findOneBy(['id' => $id]);
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

    public function getFiltersFunctions(): array
    {
        return [
            'attachment'    => 'getAttachment',
            'class_entity'  => 'classEntity',
            'formClass'     => 'formClass',
            'formPrototype' => 'formPrototype',
            'imagefilter'   => 'imagefilter',
            'verifPhone'    => 'verifPhone',
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
