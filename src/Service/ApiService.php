<?php

namespace App\Service;

use Introvert\ApiClient;
use Introvert\Configuration;
use Psr\Log\LoggerInterface;

abstract class ApiService
{
    protected $api;
    protected $config;
    protected $logger;

    public function __construct(ApiClient $api, Configuration $config, LoggerInterface $logger)
    {
        $this->api = $api;
        $this->config = $config;
        $this->logger = $logger;
        $this->config->setHost('https://api.s1.yadrocrm.ru/tmp');
        $this->config->setApiKey('key', '23bc075b710da43f0ffb50ff9e889aed');
    }
} 

