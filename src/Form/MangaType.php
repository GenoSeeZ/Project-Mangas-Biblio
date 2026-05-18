<?php

namespace App\Form;

use App\Entity\Manga;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MangaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('author', TextType::class)
            ->add('coverImage', TextType::class)
            ->add('releaseYear', IntegerType::class)
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    'Action' => 'action',
                    'Adventure' => 'adventure',
                    'Comedy' => 'comedy',
                    'Drama' => 'drama',
                    'Fantasy' => 'fantasy',
                    'Horror' => 'horror',
                    'Romance' => 'romance',
                    'Sci-Fi' => 'sci-fi',
                    'Shonen' => 'shonen',
                    'Shojo' => 'shojo',
                    'Seinen' => 'seinen',
                    'Slice of Life' => 'slice-of-life',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Ongoing' => 'ongoing',
                    'Completed' => 'completed',
                    'Hiatus' => 'hiatus',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manga::class,
        ]);
    }
}