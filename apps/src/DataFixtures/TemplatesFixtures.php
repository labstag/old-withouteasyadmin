<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Template;
use Labstag\Lib\FixtureLib;
use Twig\Environment;

class TemplatesFixtures extends FixtureLib
{

    private Environment $twig;

    const NUMBER = 10;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
        $this->addContactEmail($manager);
        $this->addCheckedEmail($manager);
        $this->addCheckedPhone($manager);
        $this->addLostPassword($manager);
    }

    private function addLostPassword(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Changement de password %site%');
        $template->setCode('lost-password');
        $template->setHtml($this->twig->render('tpl/lost-password.html.twig'));
        $template->setText($this->twig->render('tpl/lost-password.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function addCheckedPhone(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Validation du téléphone %site%');
        $template->setCode('checked-phone');
        $template->setHtml($this->twig->render('tpl/checked-phone.html.twig'));
        $template->setText($this->twig->render('tpl/checked-phone.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function addCheckedEmail(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Validation de mail %site%');
        $template->setCode('checked-mail');
        $template->setHtml($this->twig->render('tpl/checked-email.html.twig'));
        $template->setText($this->twig->render('tpl/checked-email.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function addContactEmail(ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName('Contact %site%');
        $template->setCode('contact');
        $template->setHtml($this->twig->render('tpl/contact-email.html.twig'));
        $template->setText($this->twig->render('tpl/contact-email.txt.twig'));
        $manager->persist($template);
        $manager->flush();
    }

    private function add(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addTemplate($faker, $manager);
        }

        $manager->flush();
    }

    private function addTemplate(Generator $faker, ObjectManager $manager): void
    {
        $template = new Template();
        $template->setName($faker->unique()->colorName);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(10, true);
        $template->setHtml(str_replace("\n\n", '<br />', $content));
        $template->setText($content);
        $manager->persist($template);
    }
}
