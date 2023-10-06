<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class MessageController extends AbstractController
{
    #[Route('/messages', name: 'get_messages', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->json($messageRepository->findAll());
    }

    #[Route('/messages', name: 'create_message', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jsonData = json_decode($request->getContent(), true);

        $message = new Message();
        $message->setType($jsonData['type']);
        $message->setEmail($jsonData['email']);
        $message->setUserId($jsonData['user_id']);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->json($message);
    }

    #[Route('/messages/{id}', name: 'blog_list', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->json($message);
    }
}
