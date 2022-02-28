<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 25/02/22
 * Time: 10:12 ص
 */

namespace App\Form;


use App\Entity\QaOrganization;
use App\Model\InvitationCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvitationCollectionType extends QaType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $emails = [];
        foreach (explode(',',$options['emails']) as $email){
            $emails[$email] = $email;
        }
        $builder->add('organization', EntityType::class, [
            'class' => QaOrganization::class,
            'placeholder' => 'Choose an option',
            'attr' => [
                'class' => 'form-control'
            ],
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('org');
            },
        ]);
        $builder->add('emails', ChoiceType::class, [
            'multiple' => true,
            'placeholder' => 'Choose an option',
            'choices' => $emails,
            'attr'=>[
        'class' => 'form-control'
    ]
        ]);
        $builder->get('emails')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvitationCollection::class,
            "emails" => "",

        ]);
        $resolver->setAllowedTypes("emails", "string");
    }

}
