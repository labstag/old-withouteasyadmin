<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Template;
use Labstag\Lib\DataFixtureLib;

class TemplatesFixtures extends DataFixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $faker = $this->setFaker();
        for ($index = 0; $index < self::NUMBER_TEMPLATES; ++$index) {
            $this->addTemplate($faker);
        }
    }

    protected function addTemplate(Generator $generator): void
    {
        $template    = new Template();
        $oldTemplate = clone $template;
        $template->setName($generator->unique()->colorName());
        // @var string $content
        $content = $generator->unique()->paragraphs(10, true);
        $template->setHtml(str_replace("\n\n", "<br />\n", $content));
        $template->setText($content);

        $this->templateRequestHandler->handle($oldTemplate, $template);
    }
}
