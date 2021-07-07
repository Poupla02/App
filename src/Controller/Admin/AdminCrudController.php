<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des administrateurs')
            ->setPageTitle(Crud::PAGE_EDIT, "Edition d'un administrateur")
            ->setPageTitle(Crud::PAGE_NEW, "Création d'un administrateur")
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return
            $actions
                ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action){
                return $action->setIcon('fa fa-edit')
                    ->setLabel(false);
            })
                ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action){
                return $action->setIcon('fa fa-trash')
                    ->setLabel(false);
            })
        ;
    }

    public function configureFields(string $pageName): iterable
    {

        yield EmailField::new('email', 'Adresse mail');
        yield TextField::new('role', 'Rôle');
        switch ($pageName) {
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
            {
                yield TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class);
            }
        }

    }

}
