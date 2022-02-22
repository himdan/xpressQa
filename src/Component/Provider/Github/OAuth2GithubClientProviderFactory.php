<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 21/02/22
 * Time: 12:38 م
 */

namespace App\Component\Provider\Github;


use League\OAuth2\Client\Provider\Github;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OAuth2GithubClientProviderFactory
{
    /**
     * @var Github
     */
    private $provider;

    public function __construct(ParameterBagInterface $bag, UrlGeneratorInterface $urlGenerator)
    {

        $this->provider = new Github([
            'clientId' => $bag->get('OAUTH_GITHUB_CLIENT_ID'),
            'clientSecret' => $bag->get('OAUTH_GITHUB_CLIENT_SECRET'),
            'redirectUri' => $urlGenerator->generate($bag->get('OAUTH_GITHUB_CLIENT_REDIRECT_ROUTE'),[],UrlGenerator::ABSOLUTE_URL )
        ]);
    }

    /**
     * @return Github
     */
    public function getProvider(): Github
    {
        return $this->provider;
    }




}
