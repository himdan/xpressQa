<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 12:27 ص
 */

namespace App\Component\Provider\Github;


use App\Entity\QaAccessToken;
use App\Entity\QaUser;
use Doctrine\ORM\EntityManagerInterface;
use Github\AuthMethod;
use Github\Client as GithubClient;

class GithubApiClientFactory
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    /**
     * @var GithubClient
     */
    private $apiClient;

    /**
     * GithubApiClientFactory constructor.
     * @param EntityManagerInterface $em
     * @param GithubClient $apiClient
     */
    public function __construct(EntityManagerInterface $em, GithubClient $apiClient)
    {
        $this->em = $em;
        $this->apiClient = $apiClient;
    }

    /**
     * @param QaUser $qaUser
     * @return GithubClient
     */
    public function configureWithUser(QaUser $qaUser)
    {
        $accessToken = $this->getAccessTokenByUser($qaUser);
        if($accessToken instanceof QaAccessToken){
            $this->apiClient->authenticate($accessToken->getToken(), null, AuthMethod::ACCESS_TOKEN);
            return $this->apiClient;
        }else {
            return $this->apiClient;
        }
    }

    /**
     * @param QaUser $user
     * @return object|null
     */
    private function  getAccessTokenByUser(QaUser $user)
    {
        return $this->em->getRepository(QaAccessToken::class)->findOneBy(['user'=>$user]);
    }


}
