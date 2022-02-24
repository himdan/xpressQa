<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 05:26 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class QaInvitation
 * @package App\Entity
 * @ORM\Entity()
 */
class QaInvitation
{
    use TenantTrait;
    /**
     * @var QaProvider
     */
    private $provider;
    /**
     * @var string
     */
    private $uuid;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $organizationUuid;
    /**
     * @var string $email
     */
    private $email;
}
