<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 03:47 م
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class QaAccessToken
 * @package App\Entity
 * @ORM\Entity()
 */
class QaAccessToken implements UserInterface
{
    use TenantTrait;

    protected function __construct()
    {
    }

    /**
     * @var QaProvider|null
     * @ORM\ManyToOne(targetEntity=QaProvider::class)
     */
    private $provider;
    /**
     * @var QaUser|null
     * @ORM\ManyToOne(targetEntity=QaUser::class)
     */
    private $user;
    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $token;
    /**
     * @ORM\Column(type="string")
     * @var string|null $uuid
     */
    private $uuid;

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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getRoles()
    {
        return $this->getUser()->getRoles();
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        $this->getUser()->getUsername();
    }

    public function getUserIdentifier()
    {
        $this->getUser()->getUserIdentifier();
    }

    /**
     * @return QaAccessToken
     */
    public static function initToken()
    {
        $user = new QaUser();
        $accessToken = new self();
        $accessToken->setUser($user);
        return $accessToken;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     */
    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }




}
