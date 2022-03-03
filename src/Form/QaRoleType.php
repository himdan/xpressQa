<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 03/03/22
 * Time: 12:58 م
 */

namespace App\Form;


use App\Component\Security\DefaultSecurityRoleProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class QaRoleType extends AbstractType
{

    /**
     * @var DefaultSecurityRoleProvider $roleProvider
     */
    private $roleProvider;

    /**
     * QaRoleType constructor.
     * @param DefaultSecurityRoleProvider $roleProvider
     */
    public function __construct(DefaultSecurityRoleProvider $roleProvider)
    {
        $this->roleProvider = $roleProvider;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'placeholder' => 'roles',
            'hierarchy' => 'org',
            'multiple' => true
        ]);
        $resolver->setAllowedTypes('hierarchy', ['null', 'string']);

        $resolver->setNormalizer('choices', function (Options $options, $choices) {
            $hierarchy = $options->offsetGet('hierarchy');
            $roles = $this->getRolesByHierarchy($hierarchy);
            return array_combine($roles, $roles);

        });


    }

    private function getRolesByHierarchy($hierarchy = null)
    {
        $roles = array_keys($this->roleProvider->getRoles());
        if(!$hierarchy){
            return $roles;
        }elseif(is_string($hierarchy)){
            if(strtoupper($hierarchy) !== 'ORG'){
                return $roles;
            }
            $hierarchyFormatted = strtoupper(sprintf('_%s_', $hierarchy));
            return array_filter($roles, function($role)use($hierarchyFormatted){
                return strpos($role, $hierarchyFormatted)!==false;
            });
        } else {
            return $roles;
        }
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

}
