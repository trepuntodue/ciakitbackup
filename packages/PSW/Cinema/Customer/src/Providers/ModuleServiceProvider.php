<?php

namespace PSW\Cinema\Customer\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \PSW\Cinema\Customer\Models\CustomerRelease::class,

    ];
}