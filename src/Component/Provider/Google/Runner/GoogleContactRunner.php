<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 24/02/22
 * Time: 12:18 م
 */

namespace App\Component\Provider\Google\Runner;


use Google\Service\PeopleService;

class GoogleContactRunner
{
    /**
     * @var PeopleService $peopleService
     */
    private $peopleService;
    /**
     * @var \Google_Client
     */
    private $googleClient;
    /**
     * @var int
     */
    private $pageSize = 10;
    private $readMask = 'metadata,names,emailAddresses,photos';

    /**
     * GoogleContactRunner constructor.
     * @param PeopleService $peopleService
     * @param \Google_Client $googleClient
     */
    public function __construct(PeopleService $peopleService, \Google_Client $googleClient)
    {
        $this->peopleService = $peopleService;
        $this->googleClient = $googleClient;
    }

    private function configureClient(string $state, string  $code){
        $this->googleClient->setState($state);
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($code);
        $this->googleClient->setAccessToken($token);
    }

    /**
     * @param int $pageSize
     * @return GoogleContactRunner
     */
    public function setPageSize(int $pageSize): GoogleContactRunner
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @param string $readMask
     * @return GoogleContactRunner
     */
    public function setReadMask(string $readMask): GoogleContactRunner
    {
        $this->readMask = $readMask;
        return $this;
    }

    /**
     * @return \Google_Client
     */
    public function getGoogleClient(): \Google_Client
    {
        return $this->googleClient;
    }




    /**
     * @param $state
     * @param $code
     * @param callable $worker
     */
    public function iterateOverOtherContact($state, $code, callable $worker)
    {
        $this->configureClient($state, $code);
        $optParams = array(
            'pageSize' => $this->pageSize,
            'readMask' => $this->readMask
        );
        $results = $this->peopleService->otherContacts->listOtherContacts($optParams);
        $pages = ceil($results->getTotalSize() / $optParams['pageSize']);
        for ($iteration = 0; $iteration < $pages; $iteration++) {
            foreach ($results->getOtherContacts() as $person) {
                call_user_func($worker, $person);
            }
            $results->next();
        }


    }


}
