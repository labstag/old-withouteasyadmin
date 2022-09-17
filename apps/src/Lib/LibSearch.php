<?php

namespace Labstag\Lib;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;

abstract class LibSearch
{

    public $limit;

    public function search(array $get, $doctrine)
    {
        $userRepo     = $doctrine->getRepository(User::class);
        $categoryRepo = $doctrine->getRepository(Category::class);
        $groupeRepo   = $doctrine->getRepository(Groupe::class);
        $dateTime     = new DateTime();
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            if ('published' == $key) {
                if (!empty($value)) {
                    [
                        $year,
                        $month,
                        $day,
                    ] = explode('-', (string) $value);
                    $dateTime->setDate($year, $month, $day);
                    $this->{$key} = $dateTime;

                    continue;
                }

                $this->{$key} = null;

                continue;
            }

            $this->{$key} = ('refuser' == $key) ? $userRepo->find($value) : $this->{$key};
            $this->{$key} = ('refcategory' == $key) ? $categoryRepo->find($value) : $this->{$key};
            $this->{$key} = ('refgroup' == $key) ? $groupeRepo->find($value) : $this->{$key};
        }
    }
}
