<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\Lib\LibSearch;

class PostSearch extends LibSearch
{

    public $etape;

    public $published;

    public $refcategory;

    public $refuser;

    public $title;

    public function search(array $get, $doctrine)
    {
        $date         = new DateTime();
        $userRepo     = $doctrine->getRepository(User::class);
        $categoryRepo = $doctrine->getRepository(Category::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            if ('published' == $key) {
                if (!empty($value)) {
                    [
                        $year,
                        $month,
                        $day,
                    ] = explode('-', $value);
                    $date->setDate($year, $month, $day);
                    $this->{$key} = $date;

                    continue;
                }

                $this->{$key} = null;

                continue;
            }

            $this->{$key} = ('refuser' == $key) ? $userRepo->find($value) : $this->{$key};
            $this->{$key} = ('refcategory' == $key) ? $categoryRepo->find($value) : $this->{$key};
        }
    }
}
