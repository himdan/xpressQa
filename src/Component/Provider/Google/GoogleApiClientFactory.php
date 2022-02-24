<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 23/02/22
 * Time: 11:41 م
 */

namespace App\Component\Provider\Google;


use Google\Service\PeopleService;

class GoogleApiClientFactory
{
    /**
     * @var \Google_Client $googleClient
     */
    private $googleClient;

    /**
     * GoogleApiClientFactory constructor.
     * @param \Google_Client $googleClient
     */
    public function __construct(\Google_Client $googleClient)
    {
        $this->googleClient = $googleClient;
    }

    public function createPeopleService(){
        return new PeopleService($this->googleClient);
    }


}
