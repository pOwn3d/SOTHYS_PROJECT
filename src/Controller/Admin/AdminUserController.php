<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminUserController extends AbstractCrudController
{


    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $deleteUser = Action::new('deleteUser text-danger', 'delete', 'fa fa-delete')
            ->linkToRoute('delete_user_admin', function (User $entity) {
                return [
                    'email'        => $entity->getEmail(),
                    '_switch_user' => "reset_admin"
                ];
            });

//        $newUser = Action::new('editUser text-info', 'Editer', 'fa fa-edit')
//            ->linkToRoute('edit_user_admin', function (User $entity) {
//                return [
//                    'email'        => $entity->getEmail(),
//                    '_switch_user' => "reset_admin"
//                ];
//            });


        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn(Action $action) => $action->setIcon('fa fa-user-plus')->setLabel('new.user'))
            ->add(Crud::PAGE_INDEX, $deleteUser)
//            ->add(Crud::PAGE_INDEX, $newUser)
//            ->disable(Action::DELETE, Action::EDIT);
            ->disable(Action::DELETE);
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('email', 'email'),
            BooleanField::new('accountActivated', 'actif.account'),
            BooleanField::new('isVerified', 'verified.email'),
            TextField::new('password', 'password',)
                ->setFormType(PasswordType::class)
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms(),


            ChoiceField::new('roles', 'roles')
                ->allowMultipleChoices()
                ->autocomplete()
                ->setChoices([
                        'admin' => 'ROLE_SUPER_ADMIN'
                    ]
                ),
            AssociationField::new('societyID', 'id.society')
        ];
    }


}
