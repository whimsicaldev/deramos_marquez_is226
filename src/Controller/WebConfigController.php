<?php

namespace App\Controller;

use App\Entity\WebConfig;
use App\Form\WebConfigType;
use App\Repository\WebConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/web/config')]
class WebConfigController extends AbstractController
{
    #[Route('/', name: 'app_web_config_index', methods: ['GET', 'POST'])]
    public function index(Request $request, WebConfigRepository $webConfigRepository): Response
    {
        $webConfig = $webConfigRepository->findAll()? $webConfigRepository->findAll()[0]: new WebConfig();
        $form = $this->createForm(WebConfigType::class, $webConfig);
        $form->handleRequest($request);
        $toastMessage = false;
        $toastType = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $webConfigRepository->add($webConfig);
            $toastMessage = 'Terms and conditions updated.';
            $toastType = 'success';
        }

        return $this->renderForm('web_config/index.html.twig', [
            'web_config' => $webConfig,
            'form' => $form,
            'toastMessage' => $toastMessage,
            'toastType' => $toastType
        ]);
    }

    #[Route('/new', name: 'app_web_config_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WebConfigRepository $webConfigRepository): Response
    {
        $webConfig = new WebConfig();
        $form = $this->createForm(WebConfigType::class, $webConfig);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $webConfigRepository->add($webConfig);
            return $this->redirectToRoute('app_web_config_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('web_config/new.html.twig', [
            'web_config' => $webConfig,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_web_config_show', methods: ['GET'])]
    public function show(WebConfig $webConfig): Response
    {
        return $this->render('web_config/show.html.twig', [
            'web_config' => $webConfig,
        ]);
    }
}
