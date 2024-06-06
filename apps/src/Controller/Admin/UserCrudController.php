<?php

namespace Labstag\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Labstag\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        $actions->add(Crud::PAGE_EDIT, Action::INDEX);
        $actions->add(Crud::PAGE_EDIT, Action::DETAIL);

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $repository = $this->container->get('doctrine')->getManagerForClass(static::getEntityFqcn())->getRepository(static::getEntityFqcn());
        $crud->setEntityLabelInSingular('Utilisateur');
        $crud->setEntityLabelInPlural('Utilisateurs ('.$repository->count([]).')');

        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('username');
        yield AssociationField::new('refgroupe', 'Groupe');
        yield EmailField::new('email');
        yield AssociationField::new('addressUsers', 'Adresses');
        yield AssociationField::new('linkUsers', 'Liens');
        yield AssociationField::new('emailUsers', 'Emails');
        yield AssociationField::new('phoneUsers', 'Téléphones');
        // yield TextField::new('title');
        // yield TextEditorField::new('description'),
    }
}
