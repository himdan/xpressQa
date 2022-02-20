<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 03:39 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class QaProvider
 * @package App\Entity
 * @ORM\Entity()
 */
class QaProvider
{

    use TenantTrait;
    /**
     * @var string|null
     */
    private $name;


}
