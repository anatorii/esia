<?php

namespace Esia\Http\Exceptions;

use Psr\Http\Client\ClientExceptionInterface as ClientException;

class HttpException  extends \RuntimeException implements ClientException
{
}
