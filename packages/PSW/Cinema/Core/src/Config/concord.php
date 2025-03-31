<?php

return [

    'convention' => PSW\Cinema\Core\CoreConvention::class,

    'modules' => [

        /**
         * Example:
         * VendorA\ModuleX\Providers\ModuleServiceProvider::class,
         * VendorB\ModuleY\Providers\ModuleServiceProvider::class
         *
         */

        \PSW\Cinema\Film\Providers\ModuleServiceProvider::class,
        \PSW\Cinema\Providers\ModuleServiceProvider::class,
        \PSW\Cinema\Shop\Providers\ModuleServiceProvider::class,
        \PSW\Cinema\Core\Providers\ModuleServiceProvider::class,
        \PSW\Cinema\Customer\Providers\ModuleServiceProvider::class,
    ],
];
