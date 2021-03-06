<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DownloadFileController extends AbstractController
{
    /**
     * @Route("/download/{urlFile}", name="download_file")
     **/
    public function downloadFileAction($urlFile){
        $path = $this->getParameter('document_directory');
        $response = new BinaryFileResponse($path.'/'.$urlFile);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$urlFile);
        return $response;
    }

}
