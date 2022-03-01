<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 25/02/22
 * Time: 09:38 م
 */

namespace App\Controller\Backend;


use App\Component\Provider\Google\Runner\GoogleContactRunner;
use App\Component\Security\ACL;
use App\Controller\QaController;
use App\Repository\QaAccessTokenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 * @package App\Controller\Backend
 * @Route("/admin/contact")
 */
class ContactController extends QaController
{
    /**
     * @ACL()
     * @Route("/list", name="list_contact", options={"expose":"true"})
     * @param Request $request
     * @param GoogleContactRunner $contactRunner
     * @param QaAccessTokenRepository $accessTokenRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function searchContact(
        Request $request,
        GoogleContactRunner $contactRunner,
        QaAccessTokenRepository $accessTokenRepository
    )
    {
        $user = $this->getUser();
        $refreshToken = $accessTokenRepository
            ->getTokenByProviderAndUserIdentifier(
                $user->getUserIdentifier(),
                'GOOGLE');
        $query = $request->query->get('q', 'a');
        $pageSize = $request->query->get('length', 10);
        $contactRunner->setPageSize($pageSize);
        $data = [];
        $contactRunner->searchOverOtherContact($query, $refreshToken, function ($person) use (&$data) {
            if (count($person['names'])) {
                array_push($data, ['name' => $person['names'][0]['displayName'],
                    'email' => $person['emailAddresses'][0]['value'],
                    'picture' => $person['photos'][0]['url']]);

            }
        });
        return $this->json($data);

    }
}
