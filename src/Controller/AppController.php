<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\MemberGroupAddMemberType;
use App\Repository\MemberGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(MemberGroupRepository $memberGroupRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'groups' => $memberGroupRepository->findAll(),
        ]);
    }

    
    #[Route('/group/{id}/add-member', name: 'group_add_member', methods: ['POST', 'GET'])]
    public function groupAddMember(int $id, MemberGroupRepository $memberGroupRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_SUPER_ADMIN', 
            null, 
            'User tried to access a page without having ROLE_SUPER_ADMIN'
        );
        
        $group = $memberGroupRepository->find($id);

        if (!$group) {
            throw $this->createNotFoundException(
                sprintf('No Group found for id %s', $id)
            );
        }

        $form = $this->createForm(MemberGroupAddMemberType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberGroupRepository->add($group);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('app/group/add_member.html.twig', [
            'group' => $group,
            'form'  => $form
        ]);
    }
}
