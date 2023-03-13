<?php

namespace Labstag\Event;

class ConfigurationEntityEvent
{
    public function __construct(protected array $post)
    {
    }

    public function getPost(): array
    {
        return $this->post;
    }
}
