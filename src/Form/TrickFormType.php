<?php

namespace App\Form;

use App\Entity\Groups;
use App\Entity\Tricks;
use App\Form\ImagesFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la figure'])
            ->add('group', EntityType::class, [
                'label' => 'Groupe',
                'class' => Groups::class,
                'choice_label' => 'name'
            ])
            ->add('description', TextareaType::class, ['label' => 'Description']);
            if ($options['status'] !== 'update') {
                $builder->add('images', CollectionType::class, [
                    'label' => 'Ajouter des images',
                    'entry_type' => ImagesFormType::class,
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => false
                ])
                ->add('videos', CollectionType::class, [
                    'label' => 'Ajouter des videos',
                    'entry_type' => VideosFormType::class,
                    'prototype' => true,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
            'status' => ''
        ]);
    }
}
