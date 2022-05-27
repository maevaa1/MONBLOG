<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
            yield TextField::new('title');

            yield SlugField::new('slug')->setTargetFieldName('title');

            yield TextEditorField::new('content');

            yield TextField::new('featuredText');

            yield AssociationField::new('categories');

            yield AssociationField::new('featuredImages');

            yield DateTimeField::new('createdAt')
            ->hideOnForm();

            yield DateTimeField::new('updatedAt')
            ->hideOnForm();

    }
    
}
