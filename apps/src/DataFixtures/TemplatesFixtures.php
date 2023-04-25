<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Template;
use Labstag\Lib\DataFixtureLib;

class TemplatesFixtures extends DataFixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $objectManager): void
    {
        $templates = $this->installService->getData('template');
        foreach ($templates as $key => $row) {
            $this->addTemplate($key, $row, $objectManager);
        }

        $objectManager->flush();
    }

    protected function addTemplate(
        string $key,
        string $value,
        ObjectManager $objectManager
    ): void {
        $template = new Template();
        $template->setName($value);
        $template->setCode($key);

        $htmlfile = 'tpl/mail-'.$key.'.html.twig';
        if (is_file('templates/'.$htmlfile)) {
            $template->setHtml($this->twigEnvironment->render($htmlfile));
        }

        $txtfile = 'tpl/mail-'.$key.'.txt.twig';
        if (is_file('templates/'.$txtfile)) {
            $template->setText($this->twigEnvironment->render($txtfile));
        }

        $objectManager->persist($template);
    }
}
