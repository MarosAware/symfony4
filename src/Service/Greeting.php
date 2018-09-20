<?php

namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $message;
    /**
     * @var string
     */
    private $appEnv;

    public function __construct(LoggerInterface $logger, string $message, string $appEnv)
    {
        $this->logger = $logger;
        $this->message = $message;
        $this->appEnv = $appEnv;
    }

    public function greet(string $name): string
    {
        $this->logger->info("Greeted $name");
        return "{$this->message} $name from {$this->appEnv}";
    }
}