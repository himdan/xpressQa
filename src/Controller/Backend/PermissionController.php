<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 04/03/22
 * Time: 07:04 م
 */

namespace App\Controller\Backend;


use App\Controller\QaController;
use Symfony\Component\Routing\Annotation\Route;

class PermissionController extends QaController
{
    /**
     * @Route("/admin/manage/acl", name="manage_acl")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        return $this->render('backend/pages/permission/index.html.twig', []);
    }

}
