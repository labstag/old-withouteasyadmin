<?php

namespace Labstag\Controller\Admin;

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\OperatingSystem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Labstag\Entity\HttpErrorLogs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class HttpErrorLogsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HttpErrorLogs::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $repository = $this->container->get('doctrine')->getManagerForClass(static::getEntityFqcn())->getRepository(static::getEntityFqcn());
        $crud->setEntityLabelInSingular('HTTP ERROR');
        $crud->setEntityLabelInPlural('HTTP ERRORS ('.$repository->count([]).')');

        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $maxLength = Crud::PAGE_DETAIL === $pageName ? 1024 : 32;
        yield DateField::new('created');
        yield TextField::new('url')->setMaxLength($maxLength);
        yield TextField::new('domain');
        yield TextField::new('agent')->setMaxLength($maxLength);
        yield TextField::new('internetProtocol', 'IP');
        $currentEntity = $this->getContext()->getEntity()->getInstance();
        if (!is_null($currentEntity)) {
            $deviceDetector = new DeviceDetector($currentEntity->getAgent());
            $deviceDetector->parse();
            $data = [
                'deviceDetector' => $deviceDetector,
                'currentEntity' => $currentEntity,
            ];
            yield ArrayField::new('info', 'Information')->hideOnIndex()->setValue($data)->setTemplatePath('admin/field/httperrorlogs/info.html.twig');
        }
        yield TextField::new('referer');
        yield IntegerField::new('httpCode');
        yield TextField::new('requestMethod');
        yield AssociationField::new('user', 'Utilisateur');
        if (!is_null($currentEntity)) {
            $data = $currentEntity->getRequestData();
            yield ArrayField::new('data', 'Request DATA')->hideOnIndex()->setTemplatePath('admin/field/httperrorlogs/request_data.html.twig')->setValue($data);
        }
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters->add(TextFilter::new('internetProtocol', 'IP'));
        $filters->add(NumericFilter::new('httpCode'));
        $filters->add(TextFilter::new('requestMethod'));

        return $filters;
    }
}
