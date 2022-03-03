<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 03/03/22
 * Time: 02:41 م
 */

namespace App\Form;


use App\Entity\QaMembership;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberShipType extends QaType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', QaRoleType::class, ['hierarchy'=>'org', 'multiple'=>true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> QaMembership::class
        ]);
    }

}
