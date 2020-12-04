<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Template;
use Labstag\Lib\FixtureLib;
use Labstag\Repository\UserRepository;
use Twig\Environment;
use Psr\EventDispatcher\EventDispatcherInterface;

class TemplatesFixtures extends FixtureLib
{

    private Environment $twig;

    const NUMBER = 10;

    public function __construct(
        Environment $twig,
        UserRepository $userRepository,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->twig = $twig;
        parent::__construct($userRepository, $dispatcher);
    }

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
        $data = $this->getData();
        foreach ($data as $key => $title) {
            $this->setData($key, $title, $manager);
        }

        $this->add($manager);
    }

    protected function getData(): array
    {
        $data = [
            'check-new-adresse'          => 'Ajout nouvelle adresse',
            'check-new-phone'            => 'Ajout nouveau numéro de téléphone',
            'check-new-link'             => 'Ajout nouvelle url',
            'check-user'                 => 'Confirmation création compte',
            'check-new-oauthconnectuser' => 'Nouvelle association',
            'check-new-mail'             => 'Ajout nouveau courriel',
            'change-email-principal'     => 'Changement de courriel principal',
            'lost-password'              => 'Demande de nouveau mot de passe',
            'change-password'            => 'Mot de passe changé',
        ];

        return $data;
    }

    private function setData(
        string $key,
        string $title,
        ObjectManager $manager
    ): void
    {
        $template = new Template();
        $template->setName($title);
        $template->setCode($key);
        $htmlfile = 'tpl/mail-' . $key . '.html.twig';
        if (is_file('templates/' . $htmlfile)) {
            $template->setHtml($this->twig->render($htmlfile));
        }

        $txtfile = 'tpl/mail-' . $key . '.txt.twig';
        if (is_file('templates/' . $txtfile)) {
            $template->setText($this->twig->render($txtfile));
        }

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
