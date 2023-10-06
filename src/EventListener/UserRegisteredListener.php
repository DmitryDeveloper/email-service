<?php

namespace App\EventListener;

use App\Entity\Message;
use App\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserRegisteredListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Logger $logger,
        private readonly MailerInterface $mailer
    ) {
    }

    public function __invoke(UserRegisteredEvent $event): void
    {
        $this->logger->info(__CLASS__ . ' event received!');
        $message = new Message();
        $message->setUserId($event->getUserId());
        $message->setEmail($event->getEmail());
        $message->setType($event->getType());
        //TODO delivery status

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        //TODO send email
        $email = (new Email())
            ->from('your-email@example.com')
            ->to($event->getEmail())
            ->subject('Hello!')
            ->text('Congratulations with registration.');

        $this->mailer->send($email);

        //TODO update delivery status
        $this->logger->info(__CLASS__ . ' event processed!');
    }
}
