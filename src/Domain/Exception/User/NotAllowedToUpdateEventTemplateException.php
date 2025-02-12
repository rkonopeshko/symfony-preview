<?php

declare(strict_types=1);

namespace App\Domain\Exception\User;

use Exception;

//todo разнести exception
class NotAllowedToUpdateEventTemplateException extends Exception
{
    //TODO: ПЕРЕСМОТЕРТЬ ОТНОШЕНИЕ К EXCEPTIONS
//    function __construct(string $eventTemplateiDETIFIER = 'You are not allowed to update this event template', int $code = 0, ?Exception $previous = null)
//    {
//        parent::__construct(self::MESSAGE);
//    }

}