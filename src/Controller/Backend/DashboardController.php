<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 06:08 م
 */

namespace App\Controller\Backend;

use App\Component\Security\ACL;
use App\Controller\QaController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @package App\Controller
 * @Route("/admin")
 */
class DashboardController extends QaController
{
    /**
     * @ACL(contextGroup={"DASHBORD"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="view_dashbord")
     */
    public function index()
    {
        return $this->render('backend/pages/dash.html.twig', []);
    }

    /**
     * @ACL(contextGroup={"ADMIN ON BOARDING"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/welcome", name="view_on_bording")
     */
    public function onBoarding()
    {
        return $this->render('backend/pages/onboard.html.twig', ['on_bord'=>true]);
    }

    /**
     * @ACL(contextGroup={"ADMIN ON BOARDING"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/load/contact", name="app_load_contact", options={"expose":"true"})
     */
    public function loadContact()
    {
        return $this->render('common/iframe.html.twig',[]);
    }
}
