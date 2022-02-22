<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 22/02/22
 * Time: 02:42 م
 */

namespace App\Controller\Common;


use App\Controller\QaController;
use App\Datatable\ProviderDatatable;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QaProviderController
 * @package App\Controller\Common
 * @Route("/providers")
 */
class QaProviderController extends QaController
{
    /**
     * @param Request $request
     * @param ProviderDatatable $dtFactory
     * @return Response
     * @Route("/list", name="list_provider")
     */
    public function search(Request $request, ProviderDatatable $dtFactory)
    {
        $context = $this->getContext($request);

        $datatable = $dtFactory->createDatatableManager();
        $content = $datatable->JsonSerialize($context, ['groups' => ['all']]);
        $response = new Response($content,
            Response::HTTP_OK,
            [
                'content-type' => 'application/json'
            ]
        );
        return $response;


    }
}
