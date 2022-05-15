<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MemberGroup;
use App\Form\MemberGroupType;
use App\Repository\MemberGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/group', name: 'group_')]
class MemberGroupController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['POST', 'GET'])]
    public function create(MemberGroupRepository $memberGroupRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(
            'ROLE_SUPER_ADMIN', 
            null, 
            'User tried to access a page without having ROLE_SUPER_ADMIN'
        );
        
        $group = new MemberGroup();

        $form = $this->createForm(MemberGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberGroupRepository->add($group);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('member_group/create.html.twig', [
            'form'  => $form
        ]);
    }

    #[Route('/{id<\d+>}/edit', name: 'edit', methods: ['POST', 'GET'])]
    public function edit(int $id, MemberGroupRepository $memberGroupRepository, Request $request): Response
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

        $form = $this->createForm(MemberGroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberGroupRepository->add($group);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('member_group/edit.html.twig', [
            'group' => $group,
            'form'  => $form
        ]);
    }
}
