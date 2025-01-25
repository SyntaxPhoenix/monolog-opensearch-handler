<?php

namespace SyntaxPhoenix\MonologOpenSearchHandler;

use DateTimeInterface;
use Monolog\Formatter\NormalizerFormatter;

class OpenSearchFormatter extends NormalizerFormatter
{
    public function __construct()
    {
        parent::__construct(DateTimeInterface::ATOM);
    }
}