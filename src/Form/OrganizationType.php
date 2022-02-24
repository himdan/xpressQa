<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 24/02/22
 * Time: 04:59 م
 */

namespace App\Form;


use App\Entity\QaOrganization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain')
            ->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QaOrganization::class,
        ]);
    }

}
