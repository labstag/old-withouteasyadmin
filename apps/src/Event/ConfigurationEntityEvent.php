<?php

namespace Labstag\Event;

class ConfigurationEntityEvent
{
    public function __construct(protected array $post)
    {
    }

    /**
     * @return mixed[]
     */
    public function getPost(): array
    {
        return $this->post;
    }
}
