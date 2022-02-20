<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 03:47 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class QaAccessToken
 * @package App\Entity
 * @ORM\Entity()
 */
class QaAccessToken
{
    use TenantTrait;
    /**
     * @var QaProvider|null
     * @ORM\ManyToOne(targetEntity=QaProvider)
     */
    private $provider;
    /**
     * @var QaUser|null
     * @ORM\ManyToOne(targetEntity="QaUser")
     */
    private $user;
    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @return QaProvider|null
     */
    public function getProvider(): ?QaProvider
    {
        return $this->provider;
    }

    /**
     * @param QaProvider|null $provider
     */
    public function setProvider(?QaProvider $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * @return QaUser|null
     */
    public function getUser(): ?QaUser
    {
        return $this->user;
    }

    /**
     * @param QaUser|null $user
     */
    public function setUser(?QaUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }



}
