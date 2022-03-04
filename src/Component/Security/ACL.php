<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 01/03/22
 * Time: 07:15 م
 */

namespace App\Component\Security;
use Doctrine\Common\Annotations\Annotation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ACL
 * @package App\Component\Security
 * @Annotation
 * @Annotation\Target("METHOD")
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class ACL extends Security
{
    const USER_CONTEXT = 1;
    const DOMAIN_CONTEXT = 2;

    private $contextGroup = [];

    /**
     * @return array
     */
    public function getContextGroup(): array
    {
        return $this->contextGroup;
    }

    /**
     * @param array $contextGroup
     */
    public function setContextGroup(array $contextGroup): void
    {
        $this->contextGroup = $contextGroup;
    }

    public function getExpression()
    {
        return parent::getExpression()??"is_granted('ACCESS_ROUTE')";
    }


}
