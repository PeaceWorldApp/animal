<?php
namespace App\Form;

use App\Entity\CatNote;
use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class
        ]);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('content',TextType::class)
        ->add('author',TextType::class)
        ->add('repeatTime',NumberType::class)
        ->add('cat',EntityType::class, [
            // looks for choices from this entity
            'class' => CatNote::class,
        
            // uses the User.username property as the visible option string
            'choice_label' => 'name'
        ])
        ->add('created',DateTimeType::class,
        ['widget'=>'single_text']
        )
        ->add('save',SubmitType::class, ['label' => 'Save'])
        ;
    }
}

?>