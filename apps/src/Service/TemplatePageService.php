<?php

namespace Labstag\Service;

class TemplatePageService
{

    protected $templates;

    public function __construct(
        $templates
    )
    {
        $this->templates = $templates;
    }

    public function getChoices()
    {
        $plugins = [];
        foreach ($this->templates as $template) {
            $class           = get_class($template);
            $plugins[$class] = $class;
        }

        return $plugins;
    }

    public function getClass($class): mixed
    {
        foreach ($this->templates as $template) {
            if (get_class($template) === $class) {
                return $template;
            }
        }

        return null;
    }
}
