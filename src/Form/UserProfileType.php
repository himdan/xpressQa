<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 03/03/22
 * Time: 02:26 م
 */

namespace App\Form;


use App\Model\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activeMemberShip', MemberShipType::class, [

        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> UserProfile::class
        ]);
    }

}
