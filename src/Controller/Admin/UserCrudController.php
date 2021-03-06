<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User getUser()
 */
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private EntityRepository $entityRepo,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $userId = $this->getUser()->getId();

        $qb = $this->$entityRepo->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.id != :userId')->setParameter('userId', $userId);

        return $qb;
    }
    
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('username');

            yield TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms();

            yield ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_ADMIN' => 'success',
                    'ROLE_AUTHOR' =>'warning'
                ])
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Auteur' => 'ROLE_AUTHOR'
                ]);
    }
    
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void 
    {
        /** @var User $user */
        $user = $entityInstance;

        $plainPassword = $user->getPassword();
        $hasherPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hasherPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }
}
