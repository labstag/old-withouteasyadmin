<?php

namespace Labstag\Paragraph;

use Embed\Embed;
use Embed\Extractor;
use Exception;
use finfo;
use Labstag\Entity\Attachment;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph;
use Labstag\Entity\Paragraph\Video;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\VideoType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\Paragraph\VideoRepository;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\AsciiSlugger;

class VideoParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'video';
    }

    public function getEntity(): string
    {
        return Video::class;
    }

    public function getForm(): string
    {
        return VideoType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('video.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'video';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function setData(Paragraph $paragraph): void
    {
        /** @var VideoRepository $videoRepository */
        $videoRepository = $this->entityManager->getRepository(Video::class);
        /** @var AttachmentRepository $attachmentRepository */
        $attachmentRepository = $this->entityManager->getRepository(Attachment::class);
        $videos               = $paragraph->getVideos();
        $video                = $videos[0];
        $url                  = $video->getUrl();
        if ('' == $url) {
            return;
        }

        $slug = null;

        try {
            $embed        = new Embed();
            $info         = $embed->get($url);
            $image        = $info->image->__toString();
            $title        = $info->title;
            $asciiSlugger = new AsciiSlugger();
            $video->setTitle($title);
            $slug = (string) $asciiSlugger->slug($video->getTitle());
            $video->setSlug($slug);
            $videoRepository->add($video);
        } catch (Exception) {
            $image = '';
        }

        $attachment = null;
        if (!is_null($video->getImage())) {
            $attachment = $attachmentRepository->FindOneBy(
                [
                    'id'        => $video->getImage()->getId(),
                    'deletedAt' => null,
                ]
            );
        }

        if ('' == $image || $attachment instanceof Attachment) {
            return;
        }

        $annotations = $this->uploadAnnotationReader->getUploadableFields($video);
        foreach ($annotations as $annotation) {
            $this->setDataAnnotation($annotation, $image, $video, $videoRepository, $slug);
        }
    }

    public function show(Video $video): ?Response
    {
        $extractor = $this->getData($video);
        if (is_null($extractor)) {
            return null;
        }

        $package    = new Package(new EmptyVersionStrategy());
        $attachment = $video->getImage();
        $image      = ($attachment instanceof Attachment) ? $package->getUrl('/'.$attachment->getName()) : null;

        if (is_null($image)) {
            $image = $extractor->image->__toString();
        }

        $metas = $extractor->getMetas();

        $datas = $metas->get('og:video:url');
        $embed = (0 != count($datas)) ? $datas[0] : null;

        return $this->render(
            $this->getTemplateFile($this->getCode($video)),
            [
                'paragraph' => $video,
                'image'     => $image,
                'data'      => $extractor,
                'embed'     => $embed,
            ]
        );
    }

    /**
     * @return class-string[]
     */
    public function useIn(): array
    {
        return [
            Chapter::class,
            Edito::class,
            History::class,
            Layout::class,
            Memo::class,
            Page::class,
            Post::class,
        ];
    }

    private function getData(Video $video): ?Extractor
    {
        $url = $video->getUrl();
        if ('' == $url) {
            return null;
        }

        $embed = new Embed();

        return $embed->get($url);
    }

    private function setDataAnnotation(
        mixed $annotation,
        string $image,
        Video $video,
        ServiceEntityRepositoryLib $serviceEntityRepositoryLib,
        string $slug
    ): void
    {
        /** @var finfo $finfo */
        $finfo         = finfo_open(FILEINFO_MIME_TYPE);
        $fileDirectory = $this->getParameter('file_directory');
        if (!is_string($fileDirectory)) {
            return;
        }

        try {
            $path    = $fileDirectory.'/'.$annotation->getPath();
            $content = file_get_contents($image);
            /** @var resource $tmpfile */
            $tmpfile = tmpfile();
            $data    = stream_get_meta_data($tmpfile);
            file_put_contents($data['uri'], $content);
            $file = new UploadedFile(
                $data['uri'],
                $slug.'.jpg',
                (string) finfo_file($finfo, $data['uri']),
                null,
                true
            );
            $clientOriginalName = $file->getClientOriginalName();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $file->move(
                $path,
                $clientOriginalName
            );
            $file       = $path.'/'.$clientOriginalName;
            $attachment = $this->fileService->setAttachment($file);
            $serviceEntityRepositoryLib->add($attachment);
            $video->setImage($attachment);
            $serviceEntityRepositoryLib->add($video);
        } catch (Exception $exception) {
            $this->errorService->set($exception);
            echo $exception->getMessage();
        }
    }
}
