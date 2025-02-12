<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception\TokenExchanger;

use Exception;
use Throwable;

final class FailedExchangeTokenException extends Exception implements Throwable
{
    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}