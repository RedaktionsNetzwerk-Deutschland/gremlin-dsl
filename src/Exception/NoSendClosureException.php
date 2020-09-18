<?php

declare(strict_types=1);

namespace RND\GremlinDSL\Exception;

use RND\GremlinDSL\Configuration;
use RuntimeException;
use Throwable;

class NoSendClosureException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = sprintf(
                'You must either configure the sendClosure or provide a closure to enable the SendStep: `%s::getInstance()->setSendClosure(function(string $traversalString) {})`',
                Configuration::class
            );
        }
        parent::__construct($message, $code, $previous);
    }
}
