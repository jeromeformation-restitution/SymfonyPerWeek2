<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('description')
            ->add('price', null, [
                'label' => 'Prix'
            ])
            ->add('isPublished', null, [
                'label' => 'Le produit doit-il être publié ?',
            ])

            /*->add('imageName', null, [
                'label' => 'Upload d\'image à venir'
            ])*/

            ->add('imageFile', FileType::class, [
                'label' => 'Choisir votre image'
            ])
            ->add('category', null, [
                'label' => 'Catégorie associée'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
