<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ParagraphBlock extends BlockLib implements BlockInterface
{
    public function getCode(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string
    {
        unset($entityBlock, $entityFront);

        return 'paragraph';
    }

    public function getEntity(): string
    {
        return Paragraph::class;
    }

    public function getForm(): string
    {
        return ParagraphType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('paragraph.name', [], 'block');
    }

    public function getType(): string
    {
        return 'paragraph';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    private function launParagraphs(array $paragraphs): array
    {
        foreach ($paragraphs as $position => $row) {
            if ($row['args']['parameters'] instanceof RedirectResponse) {
                continue;
            }
            $content = call_user_func_array([$row['class'], $row['execute']], $row['args']);
            $paragraphs[$position]['data'] = $content;
        }

        return $paragraphs;
    }

    private function setParagraphs(?EntityFrontInterface $entityFront): array
    {
        $paragraphs = [];
        if (is_null($entityFront)) {
            return $paragraphs;
        }

        $methods = get_class_methods($entityFront);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        return $this->getParagraphsArray($this->paragraphService, $entityFront, $paragraphs);
    }

    public function context(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): mixed
    {
        if (!$entityBlock instanceof Paragraph) {
            return null;
        }

        $data     = $this->setParagraphs($entityFront);
        $redirect = null;
        foreach ($data as $paragraphs) {
            if (!$paragraphs['args']['parameters'] instanceof RedirectResponse) {
                continue;
            }

            $redirect = $paragraphs['args']['parameters'];

            break;
        }

        if (!is_null($redirect)) {
            return $redirect;
        }else{
            $data = $this->launParagraphs($data);
        }

        return [
            'paragraphs' => $data,
            'block'      => $entityBlock,
        ];
    }
}
