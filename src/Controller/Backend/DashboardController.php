<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 20/02/22
 * Time: 06:08 م
 */

namespace App\Controller\Backend;

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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="app_dash")
     */
    public function index()
    {
        return $this->render('backend/pages/dash.html.twig', []);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/welcome", name="app_on_boarding")
     */
    public function onBoarding()
    {
        return $this->render('backend/pages/onboard.html.twig', ['on_bord'=>true]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/load/contact", name="app_load_contact", options={"expose":"true"})
     */
    public function loadContact()
    {
        return $this->render('common/iframe.html.twig',[]);
    }
}
