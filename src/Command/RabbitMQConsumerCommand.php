<?php

namespace App\Command;

use App\Event\UserRegisteredEvent;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:rabbitmq:consumer',
    description: 'Add a short description for your command',
)]
class RabbitMQConsumerCommand extends Command
{
    protected static $defaultName = 'app:rabbitmq:consumer';

    protected static $defaultDescription = 'Starts RabbitMQ consumer';

    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp('This command starts a consumer for RabbitMQ queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $queueName = 'UsersQueue';
        $channel->queue_declare($queueName, false, true, false, false);

        $output->writeln(" [*] Waiting for messages in $queueName. To exit press CTRL+C\n");

        $callback = function ($msg) use ($output) {
            $output->writeln(" [x] Received " . $msg->body);

            ['id' => $userId, 'email' => $email] = json_decode($msg->body, true);
            $event = new UserRegisteredEvent($userId, $email);
            $this->dispatcher->dispatch($event, UserRegisteredEvent::NAME);
        };

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
