<?php

namespace App\Form\Type;

use App\Form\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallBackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content',TextareaType::class, [
                'label' => 'Votre message'
            ])
            ->add('article', HiddenType::class)
            ->add('send', SubmitType::class, [
            'label' => 'Envoyer'
            ]);
        
        $builder->get('article')
            ->addModelTransformer(new CallBackTransformer(
                fn(Article $article) => $article->getId(),
                fn(Article $article) => $article->getTitle()));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'csrf_token_id' => 'comment-add'
        ]);
    }

}