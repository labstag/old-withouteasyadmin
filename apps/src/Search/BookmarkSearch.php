<?php

namespace Labstag\Search;

use Labstag\Entity\Category;
use Labstag\Entity\User;

class BookmarkSearch
{

    public $name;

    public $refcategory;

    public $refuser;

    public function search(array $get, $doctrine)
    {
        $userRepo     = $doctrine->getRepository(User::class);
        $categoryRepo = $doctrine->getRepository(Category::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            $this->{$key} = ('refuser' == $key) ? $userRepo->find($value) : $this->{$key};
            $this->{$key} = ('refcategory' == $key) ? $categoryRepo->find($value) : $this->{$key};
        }
    }
}
