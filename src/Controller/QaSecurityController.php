<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 21/02/22
 * Time: 01:42 ص
 */

namespace App\Controller;


use App\Entity\QaAccessToken;
use App\Entity\QaProvider;
use App\Entity\QaUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\Github;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class QaSecurityController
 * @package App\Controller
 */
class QaSecurityController extends QaController
{

    public const LOGIN_ROUTE = 'app_login';
    public const CHECK_ROUTE = 'app_check_github';


    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * QaSecurityController constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_dash');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('common/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/github_check", name="app_check_github")
     * @param Request $request
     * @param Github $provider
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function checkGithub(Request $request, Github $provider, EntityManagerInterface $em){
        if (!$request->attributes->has('code')) {
            $authUrl = $provider->getAuthorizationUrl();
            $request->getSession()->set('oauth2state', $provider->getState());
            sleep(5);
            return $this->redirect($authUrl);

        } elseif (empty($request->get('state')) || ($request->get('state') !== $request->getSession()->get('oauth2state'))) {
            $request->getSession()->remove('oauth2state');
            sleep(5);
            $this->redirect($this->getLoginUrl($request));

        } else {
            sleep(5);
            dump($request->attributes->all());

            $token = $provider->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);
            try {
                $user = $provider->getResourceOwner($token);
                $qaUser =  $em->getRepository(QaUser::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier() ]);
                $accessToken = QaAccessToken::initToken();
                $accessToken->setUuid($user->getId());
                $accessToken->setUser($qaUser);
                $provider = $this->findOrCreateProvider($em);
                $accessToken->setProvider($provider);
                $em->persist($provider);
                $em->persist($accessToken);
                $em->flush();
                return $this->redirect('/');


            } catch (\Exception $e) {
                return $this->redirect('/');
            }
        }
    }

    protected function getCheckUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::CHECK_ROUTE);
    }


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    private function findOrCreateProvider(EntityManager $em)
    {
        $provider = $em->getRepository(QaProvider::class)->findOneBy(['name' => 'GITHUB']);

        if ($provider) return $provider;
        $provider = new QaProvider();
        $provider->setName('GITHUB');

        return $provider;
    }
}
