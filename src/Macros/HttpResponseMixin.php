<?php

declare(strict_types=1);

namespace SnapServ\Dotty\Macros;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use SnapServ\Dotty\Dotty;

class HttpResponseMixin
{
    protected function jsonDotty(): \Closure
    {
        /**
         * @throws RequestException
         */
        return function (): Dotty {
            /** @var Response $this */
            return Dotty::from((array)$this->throw()->json());
        };
    }
}
