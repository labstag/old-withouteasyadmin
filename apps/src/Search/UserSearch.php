<?php

namespace Labstag\Search;

use Labstag\Entity\Groupe;
use Labstag\Lib\LibSearch;

class UserSearch extends LibSearch
{

    public $email;

    public $etape;

    public $refgroup;

    public $username;

    public function search(array $get, $doctrine)
    {
        $groupeRepo = $doctrine->getRepository(Groupe::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            $this->{$key} = ('refgroup' == $key) ? $groupeRepo->find($value) : $this->{$key};
        }
    }
}
