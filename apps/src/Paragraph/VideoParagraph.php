<?php

namespace Labstag\Paragraph;

use Embed\Embed;
use Exception;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\String\Slugger\AsciiSlugger;

class VideoParagraph extends ParagraphLib
{
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
        $videos = $paragraph->getVideos();
        $video = $videos[0];

        if (!$this->uploadAnnotationReader->isUploadable($video)) {
            return;
        }

        $url = $video->getUrl();

        try {
            $asciiSlugger = new AsciiSlugger();
            $slug = $asciiSlugger->slug($video->getTitle());
            $embed = new Embed();
            $info = $embed->get($url);
            $image = $info->image->__toString();
            $title = $info->title;
            $video->setSlug($slug);
            $video->setTitle($title);
            /** @var VideoRepository $repository */
            $repository = $this->entityManager->getRepository(Video::class);
            $repository->add($video);
        } catch (Exception $exception) {
            $image = '';
        }

        if ('' == $image) {
            return;
        }

        try {
            $annotations = $this->uploadAnnotationReader->getUploadableFields($video);
            foreach ($annotations as $annotation) {
                $path = $this->getParameter('file_directory').'/'.$annotation->getPath();
                $accessor = PropertyAccess::createPropertyAccessor();
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
                $filename = $file->getClientOriginalName();
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $file->move(
                    $path,
                    $filename
                );
                $file = $path.'/'.$filename;

                if (isset($filename)) {
                    $attachment = $this->fileService->setAttachment($file);
                    $video->setImage($attachment);
                    $repository->add($video);
                }
            }
        } catch (Exception $exception) {
            $this->errorService->set($exception);
            echo $exception->getMessage();
        }
    }

    public function show(Video $video): Response
    {
        return $this->render(
            $this->getParagraphFile('video'),
            ['paragraph' => $video]
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
}
