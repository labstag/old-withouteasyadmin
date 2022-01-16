<?php

namespace Labstag\Service;

class TemplatePageService
{
    public function __construct(protected $templates)
    {
    }

    public function getChoices()
    {
        $plugins = [];
        foreach ($this->templates as $template) {
            $plugins[$template::class] = $template::class;
        }

        return $plugins;
    }

    public function getClass($class): mixed
    {
        foreach ($this->templates as $template) {
            if ($template::class === $class) {
                return $template;
            }
        }

        return null;
    }
}
