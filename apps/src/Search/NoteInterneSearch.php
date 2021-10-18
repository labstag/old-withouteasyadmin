<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\User;

class NoteInterneSearch
{

    public $dateDebut;

    public $dateFin;

    public $etape;

    public $refuser;

    public $title;

    public function search(array $get, $doctrine)
    {
        $date     = new DateTime();
        $userRepo = $doctrine->getRepository(User::class);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            if ('dateDebut' == $key || 'dateFin' == $key) {
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
        }
    }
}
