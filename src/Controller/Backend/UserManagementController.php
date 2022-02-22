<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 06:57 م
 */

namespace App\Controller\Backend;

use App\Controller\QaController;
use App\Datatable\UserDatatable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserManagementController
 * @package App\Controller\Backend
 * @Route("/admin/users")
 */
class UserManagementController extends QaController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/")
     */
    public function index()
    {
        return $this->render('backend/pages/user-management/index.html.twig', []);
    }

    /**
     * @param Request $request
     * @param UserDatatable $userDatatable
     * @return Response
     * @Route("/list", name="list_users")
     */
    public function search(Request $request, UserDatatable $userDatatable)
    {
        $context = $this->getContext($request);

        $datatable = $userDatatable->createDatatableManager();
        $content = $datatable->JsonSerialize($context, ['groups' => ['admin_user']]);
        $response = new Response($content,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
        return $response;


    }
}
