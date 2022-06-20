<?php
namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name')
        ->add('description')
        ->add('id', HiddenType::class);
        // ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $product = $event->getData();
        //     $form = $event->getForm();
        //     if (!$product || null === $product->getId()) {
        //         $form->add('name', TextType::class);
        //         $form->add('description', TextType::class);
        //     }
        // })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

?>