<?php

namespace Labstag\Search\User;

use Labstag\Entity\User;
use Labstag\Lib\LibSearch;

class AddressUserSearch extends LibSearch
{

    public $city;

    public $country;

    public $refuser;

    public function search(array $get, $doctrine)
    {
        $userRepo = $doctrine->getRepository(User::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;

            $this->{$key} = ('refuser' == $key) ? $userRepo->find($value) : $this->{$key};
        }
    }
}
