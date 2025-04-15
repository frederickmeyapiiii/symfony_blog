<?php
namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Ajouter ceci
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Title'])
            ->add('content', TextareaType::class, ['label' => 'Content'])
            ->add('imageFile', FileType::class, [ // Ajouter ce champ
                'label' => 'Image (PNG, JPG)',
                'required' => false,
                'mapped' => false, // Cette ligne indique que ce champ ne correspond pas à une propriété de l'entité
            ])
            ->add('save', SubmitType::class, ['label' => 'Save Article']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}