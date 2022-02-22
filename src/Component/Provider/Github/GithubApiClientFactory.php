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
use App\Repository\QaAccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Github\AuthMethod;
use Github\Client as GithubClient;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @var Security
     */
    private $security;

    /**
     * GithubApiClientFactory constructor.
     * @param EntityManagerInterface $em
     * @param GithubClient $apiClient
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, GithubClient $apiClient, Security $security)
    {
        $this->em = $em;
        $this->apiClient = $apiClient;
        $this->security = $security;
    }

    /**
     * @return GithubClient
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function configure(){
        $user = $this->security->getUser();
        if($user instanceof UserInterface){
            $dbUser = $this->em->getRepository(QaUser::class)->findOneBy(['email' => $user->getUserIdentifier()]);
            if($dbUser instanceof QaUser ){
                return $this->configureWithUser($dbUser);
            }
        }
        throw new \Exception();
    }
    /**
     * @param QaUser $qaUser
     * @return GithubClient
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * @return QaAccessToken|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function  getAccessTokenByUser(QaUser $user)
    {
        /**
         * @var QaAccessTokenRepository $qaAccessTokenRepository
         */
        $qaAccessTokenRepository = $this->em->getRepository(QaAccessToken::class);
        return $qaAccessTokenRepository->getTokenByProviderAndUser($user, 'GITHUB');
    }


}
