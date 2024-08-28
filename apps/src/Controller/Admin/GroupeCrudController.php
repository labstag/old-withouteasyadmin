<?php

namespace Labstag\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Labstag\Entity\Groupe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Groupe::class;
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
        $crud->setEntityLabelInSingular('Groupe');
        $crud->setEntityLabelInPlural('Groupes ('.$repository->count([]).')');

        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code');
        yield TextField::new('name', 'Nom');
        yield AssociationField::new('users', 'Utilisateurs')->hideOnForm();
    }
}
