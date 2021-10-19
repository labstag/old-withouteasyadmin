<?php

namespace Labstag\Search;

use Labstag\Entity\Groupe;

class UserSearch
{

    public $email;

    public $etape;

    public $refgroupe;

    public $username;

    public function search(array $get, $doctrine)
    {
        $groupeRepo = $doctrine->getRepository(Groupe::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            $this->{$key} = ('refgroupe' == $key) ? $groupeRepo->find($value) : $this->{$key};
        }
    }
}
