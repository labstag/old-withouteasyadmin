<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\Template;
use Labstag\Lib\FixtureLib;

class TemplatesFixtures extends FixtureLib implements DependentFixtureInterface
{
    const NUMBER = 10;

    public function load(ObjectManager $manager): void
    {
        $this->add($manager);
        $data = $this->getData();
        foreach ($data as $key => $title) {
            $this->setData($key, $title);
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

    protected function setData(
        string $key,
        string $title
    ): void {
        $template    = new Template();
        $oldTemplate = clone $template;
        $template->setName($title);
        $template->setCode($key);
        $htmlfile = 'tpl/mail-'.$key.'.html.twig';
        if (is_file('templates/'.$htmlfile)) {
            $template->setHtml($this->twig->render($htmlfile));
        }

        $txtfile = 'tpl/mail-'.$key.'.txt.twig';
        if (is_file('templates/'.$txtfile)) {
            $template->setText($this->twig->render($txtfile));
        }

        $this->templateRH->handle($oldTemplate, $template);
    }

    protected function add(ObjectManager $manager): void
    {
        unset($manager);
        $faker = Factory::create('fr_FR');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addTemplate($faker);
        }
    }

    public function getDependencies()
    {
        return [DataFixtures::class];
    }

    protected function addTemplate(Generator $faker): void
    {
        $template    = new Template();
        $oldTemplate = clone $template;
        $template->setName($faker->unique()->colorName);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(10, true);
        $template->setHtml(str_replace("\n\n", '<br />', $content));
        $template->setText($content);
        $this->templateRH->handle($oldTemplate, $template);
    }
}
