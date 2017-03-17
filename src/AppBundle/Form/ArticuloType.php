<?php

namespace AppBundle\Form;
use AppBundle\Entity\Articulo;
use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticuloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', TextType::class, ['error_bubbling' => true])
            ->add('cuerpo', TextType::class, ['error_bubbling' => true])
            ->add('Enviar', SubmitType::class);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Articulo'
        ]);
    }
    public function getBlockPrefix()
    {
        return 'app_bundle_articulo_type';
    }
}
