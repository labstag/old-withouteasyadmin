<?php

namespace Labstag\Paragraph;

use Embed\Embed;
use Exception;
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
use Labstag\Lib\ParagraphLib;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\AsciiSlugger;

class VideoParagraph extends ParagraphLib
{
    public function getCode($video): string
    {
        unset($video);

        return 'video';
    }

    public function getData(Video $video)
    {
        $url = $video->getUrl();
        if ('' == $url) {
            return [];
        }

        $embed = new Embed();

        return $embed->get($url);
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

    public function setData(Paragraph $paragraph)
    {
        /** @var VideoRepository $videoRepository */
        $videoRepository = $this->entityManager->getRepository(Video::class);
        /** @var AttachmentRepository $attachmentRepository */
        $attachmentRepository = $this->entityManager->getRepository(Attachment::class);
        $collection = $paragraph->getVideos();
        $video = $collection[0];
        $url = $video->getUrl();
        if ('' == $url) {
            return;
        }

        try {
            $embed = new Embed();
            $info = $embed->get($url);
            $image = $info->image->__toString();
            $title = $info->title;
            $asciiSlugger = new AsciiSlugger();
            $video->setTitle($title);
            $slug = $asciiSlugger->slug($video->getTitle());
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

    public function show(Video $video): Response
    {
        $data = $this->getData($video);

        $package = new Package(new EmptyVersionStrategy());
        $image = ($video->getImage() instanceof Attachment) ? $package->getUrl('/'.$video->getImage()->getName()) : null;

        if (is_null($image)) {
            $image = $data->image->__toString();
        }

        $metas = $data->getMetas();

        $datas = $metas->get('og:video:url');
        $embed = (0 != count($datas)) ? $datas[0] : null;

        return $this->render(
            $this->getTemplateFile($this->getCode($video)),
            [
                'paragraph' => $video,
                'image'     => $image,
                'data'      => $data,
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

    private function setDataAnnotation($annotation, $image, $video, $repository, $slug)
    {
        try {
            $path = $this->getParameter('file_directory').'/'.$annotation->getPath();
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $content = file_get_contents($image);
            // @var resource $tmpfile
            $tmpfile = tmpfile();
            $data = stream_get_meta_data($tmpfile);
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
            $file = $path.'/'.$clientOriginalName;

            if (isset($clientOriginalName)) {
                $attachment = $this->fileService->setAttachment($file);
                $repository->add($attachment);
                $video->setImage($attachment);
                $repository->add($video);
            }
        } catch (Exception $exception) {
            $this->errorService->set($exception);
            echo $exception->getMessage();
        }
    }
}
