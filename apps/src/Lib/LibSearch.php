<?php

namespace Labstag\Lib;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;

abstract class LibSearch
{

    public $limit;

    protected function __get($prop)
    {
        return $this->{$prop};
    }

    protected function __set($prop, $val)
    {
        $this->{$prop} = $val;
    }

    public function search(array $get, $doctrine)
    {
        $userRepo     = $doctrine->getRepository(User::class);
        $categoryRepo = $doctrine->getRepository(Category::class);
        $groupeRepo   = $doctrine->getRepository(Groupe::class);
        $date         = new DateTime();
        foreach ($get as $key => $value) {
            $this->__set($key, $value);
            if ('published' == $key) {
                if (!empty($value)) {
                    [
                        $year,
                        $month,
                        $day,
                    ] = explode('-', (string) $value);
                    $date->setDate($year, $month, $day);
                    $this->__set($key, $date);

                    continue;
                }

                $this->__set($key, null);

                continue;
            }

            $this->__set($key, ('refuser' == $key) ? $userRepo->find($value) : $this->__get($key));
            $this->__set($key, ('refcategory' == $key) ? $categoryRepo->find($value) : $this->__get($key));
            $this->__set($key, ('refgroup' == $key) ? $groupeRepo->find($value) : $this->__get($key));
        }
    }
}
