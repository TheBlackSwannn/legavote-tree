<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Tree;

class TreeController extends AbstractController
{
    #[Route('/', name: 'tree_index')]
    public function index(SessionInterface $session): Response
    {
        if (!$session->has('tree')) {
            $session->set('tree', new Tree());
        }

        return $this->render('index.html.twig', [
            'tree' => $session->get('tree'),
        ]);
    }

    #[Route('/grow', name: 'tree_grow')]
    public function grow(SessionInterface $session): JsonResponse
    {
        if (!$session->has('tree')) {
            return new JsonResponse([
                'error' => 'No tree in session',
            ], Response::HTTP_BAD_REQUEST);
        }

        $tree = $session->get('tree');

        $tree->grow();

        return new JsonResponse([
            'tree' => $tree->toArray(),
            'metrics' => $tree->getMetrics(),
        ]);
    }

    #[Route('/reset-session', name: 'reset_session')]
    public function resetSession(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('tree_index');
    }
}
