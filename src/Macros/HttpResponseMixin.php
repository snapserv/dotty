<?php

declare(strict_types=1);

namespace SnapServ\Dotty\Macros;

use SnapServ\Dotty\Dotty;

class HttpResponseMixin
{
    protected function jsonDotty(): \Closure
    {
        return function (): Dotty {
            /** @var \Illuminate\Http\Client\Response $this */
            return Dotty::from((array)$this->throw()->json());
        };
    }
}
