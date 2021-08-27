<?php

namespace Labstag\Event;

class ConfigurationEntityEvent
{

    protected array $post;

    public function __construct(array $post)
    {
        $this->post = $post;
    }

    public function getPost(): array
    {
        return $this->post;
    }
}
