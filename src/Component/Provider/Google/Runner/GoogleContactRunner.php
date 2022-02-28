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

    private function configureClient(string $refreshToken){
        $token = $this->googleClient->fetchAccessTokenWithRefreshToken($refreshToken);
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
     * @param string $refreshToken
     * @param callable $worker
     */
    public function iterateOverOtherContact(string $refreshToken, callable $worker)
    {
        $this->configureClient($refreshToken);
        $optParams = array(
            'pageSize' => $this->pageSize,
            'readMask' => $this->readMask,
        );
        do{
            $results = $this->peopleService->otherContacts->listOtherContacts($optParams);
            foreach ($results->getOtherContacts() as $person) {
                call_user_func($worker, $person);
            }
            $nextPageToken = $results->getNextPageToken();
            if($nextPageToken){
                $optParams['pageToken'] = $nextPageToken;
            } else {
                break;
            }
        }while(true);

    }

    public function searchOverOtherContact(string $query, string $refreshToken, callable $worker)
    {
        $this->configureClient($refreshToken);
        $optParams = array(
            'pageSize' => $this->pageSize,
            'readMask' => $this->readMask,
            'query'=> $query
        );

        $search = $this->peopleService->otherContacts->search($optParams);
        foreach ($search->getResults() as $result){
            call_user_func($worker, $result->getPerson());
        }
    }


}
