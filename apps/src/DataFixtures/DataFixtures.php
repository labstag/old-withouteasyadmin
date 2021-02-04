<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Labstag\Lib\FixtureLib;

class DataFixtures extends FixtureLib
{
    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $this->cache->clear();
        $folder = 'public/uploads';
        if (is_dir($folder)) {
            $this->delTree($folder);
        }
    }

    protected function delTree(string $dir): bool
    {
        $files = array_diff((array) scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir($dir.'/'.$file)) {
                $this->delTree($dir.'/'.$file);

                continue;
            }

            unlink($dir.'/'.$file);
        }

        return rmdir($dir);
    }
}
