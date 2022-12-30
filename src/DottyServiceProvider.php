<?php

declare(strict_types=1);

namespace SnapServ\Dotty;

use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\ServiceProvider;
use SnapServ\Dotty\Macros\HttpResponseMixin;

class DottyServiceProvider extends ServiceProvider
{
    /**
     * @throws \ReflectionException
     */
    public function boot(): void
    {
        HttpResponse::mixin(new HttpResponseMixin());
    }
}
