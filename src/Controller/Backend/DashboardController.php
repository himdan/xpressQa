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
     * @Route("/")
     */
    public function index()
    {
        return $this->render('backend/pages/dash.html.twig', []);
    }
}
