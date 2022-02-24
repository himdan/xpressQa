<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 21/02/22
 * Time: 01:42 ص
 */

namespace App\Controller;


use App\Component\Provider\Google\Runner\GoogleContactRunner;
use App\Entity\QaAccessToken;
use App\Entity\QaProvider;
use App\Entity\QaUser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Google\Service\PeopleService;
use League\OAuth2\Client\Provider\Github;
use Psr\Log\LoggerInterface;
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
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * QaSecurityController constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param LoggerInterface $logger
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, LoggerInterface $logger)
    {
        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
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
    public function checkGithub(Request $request, Github $provider, EntityManagerInterface $em)
    {
        if (!$request->get('code')) {
            $authUrl = $provider->getAuthorizationUrl([
                'scope' => [
                    'read:org',
                    'read:user',
                    'user:follow'
                ]
            ]);
            $request->getSession()->set('oauth2state', $provider->getState());
            return $this->redirect($authUrl);

        } elseif (empty($request->get('state')) || ($request->get('state') !== $request->getSession()->get('oauth2state'))) {
            $request->getSession()->remove('oauth2state');
            $this->redirect($this->getLoginUrl($request));

        } else {

            $token = $provider->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);
            try {
                $user = $provider->getResourceOwner($token);
                $qaUser = $em->getRepository(QaUser::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
                $accessToken = QaAccessToken::initToken();
                $accessToken->setUuid($user->getId());
                $accessToken->setUser($qaUser);
                $provider = $this->findOrCreateProvider($em);
                $accessToken->setProvider($provider);
                $accessToken->setToken($token->getToken());
                $em->persist($provider);
                $em->persist($accessToken);
                $em->flush();
                return $this->redirect($this->getLoginUrl($request));
            } catch (\Exception $e) {
                $this->logger->info(sprintf('Exception %s', $e->getMessage()));
                return $this->redirect($this->getLoginUrl($request));
            }
        }
    }

    protected function getCheckUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::CHECK_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL);
    }


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function findOrCreateProvider(EntityManager $em)
    {
        $provider = $em->getRepository(QaProvider::class)->findOneBy(['name' => 'GITHUB']);

        if ($provider) return $provider;
        $provider = new QaProvider();
        $provider->setName('GITHUB');

        return $provider;
    }

    /**
     * @Route("/google_check", name="app_check_google")
     * @param Request $request
     * @param GoogleContactRunner $contactRunner
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function checkGoogle(Request $request, GoogleContactRunner $contactRunner)
    {

        $googleClient = $contactRunner->getGoogleClient();
        $redirectUrl = $this->generateUrl('app_check_google', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $googleClient->setRedirectUri($redirectUrl);
        $googleClient->setScopes([
            \Google_Service_PeopleService::CONTACTS_READONLY,
            \Google_Service_PeopleService::CONTACTS_OTHER_READONLY,
            \Google_Service_PeopleService::USER_EMAILS_READ
        ]);
        if (!$request->get('code')) {
            $state = sha1(sprintf('sf_%s_me', time()));
            $authUrl = $googleClient->createAuthUrl();
            $request->getSession()->set('Google_OAuth2_state', $state);
            return $this->redirect($authUrl);
        }else {
            $state = $request->getSession()->get('Google_OAuth2_state');
            $code = $request->get('code');
            $persons = [];
            $contactRunner->iterateOverOtherContact($state, $code, function(PeopleService\Person $person)use(&$persons){
                array_push($persons, $person);
            });
            return $this->render('common/contact-list-html.twig', ['persons' => $persons]);

        }
    }


}
