<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Provider;

use Google\Client;

interface GoogleClientProviderInterface
{
    public function getClient(): Client;
}