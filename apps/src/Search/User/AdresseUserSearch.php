<?php

namespace Labstag\Search\User;

use Labstag\Entity\User;

class AdresseUserSearch
{

    public $country;

    public $refuser;

    public $ville;

    public function search(array $get, $doctrine)
    {
        $userRepo = $doctrine->getRepository(User::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;

            $this->{$key} = ('refuser' == $key) ? $userRepo->find($value) : $this->{$key};
        }
    }
}
