<?php

use Illuminate\Support\Facades\Facade;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Ciakit'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'https://ciakit.com'),

    /*
    |--------------------------------------------------------------------------
    | Application Admin URL
    |--------------------------------------------------------------------------
    |
    | This URL suffix is used to define the admin url for example
    | admin/ or backend/
    |
    */

    'admin_url' => env('APP_ADMIN_URL', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'Asia/Kolkata'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => env('APP_LOCALE', 'it'),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default country by country code.
    | Ensure it is uppercase and reflects the 'code' column of the
    | countries table.
    |
    | for example: DE EN FR
    | (use capital letters!)
    */

    'default_country' => null,

    /*
    |--------------------------------------------------------------------------
    | Base Currency Code
    |--------------------------------------------------------------------------
    |
    | Here you may specify the base currency code for your application.
    |
    */

    'currency' => env('APP_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Default channel Code
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default channel code for your application.
    |
    */

    'channel' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /**
     * Code editor.
     */
    'editor' => 'vscode',

    /*
     *Application Version
     */
    'version' => env('APP_VERSION', '1.x-dev'),

    /**
     * Blacklisting attributes while debugging
     */
    'debug_blacklist' => [
        '_ENV' => [
            'APP_KEY',
            'DB_PASSWORD'
        ],

        '_SERVER' => [
            'APP_KEY',
            'DB_PASSWORD'
        ],

        '_POST' => [
            'password'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers.
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers.
         */

        Astrotomic\Translatable\TranslatableServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,

        /*
         * Application Service Providers.
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        /**
         * Repository Service Providers.
         */
        Prettus\Repository\Providers\RepositoryServiceProvider::class,
        Konekt\Concord\ConcordServiceProvider::class,
        Flynsarmy\DbBladeCompiler\DbBladeCompilerServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,

        /**
         * Webkul Package Service Providers.
         */
        Webkul\Theme\Providers\ThemeServiceProvider::class,
        Webkul\User\Providers\UserServiceProvider::class,
        Webkul\Admin\Providers\AdminServiceProvider::class,
        Webkul\Ui\Providers\UiServiceProvider::class,
        Webkul\Category\Providers\CategoryServiceProvider::class,
        Webkul\Attribute\Providers\AttributeServiceProvider::class,
        Webkul\Core\Providers\CoreServiceProvider::class,
        Webkul\Core\Providers\EnvValidatorServiceProvider::class,
        Webkul\Shop\Providers\ShopServiceProvider::class,
        Webkul\Customer\Providers\CustomerServiceProvider::class,
        Webkul\Inventory\Providers\InventoryServiceProvider::class,
        Webkul\Product\Providers\ProductServiceProvider::class,
        Webkul\Checkout\Providers\CheckoutServiceProvider::class,
        Webkul\Shipping\Providers\ShippingServiceProvider::class,
        Webkul\Payment\Providers\PaymentServiceProvider::class,
        Webkul\Paypal\Providers\PaypalServiceProvider::class,
        Webkul\Sales\Providers\SalesServiceProvider::class,
        Webkul\Tax\Providers\TaxServiceProvider::class,
        Webkul\CatalogRule\Providers\CatalogRuleServiceProvider::class,
        Webkul\CartRule\Providers\CartRuleServiceProvider::class,
        Webkul\Rule\Providers\RuleServiceProvider::class,
        Webkul\CMS\Providers\CMSServiceProvider::class,
        Webkul\Velocity\Providers\VelocityServiceProvider::class,
        Webkul\BookingProduct\Providers\BookingProductServiceProvider::class,
        Webkul\SocialLogin\Providers\SocialLoginServiceProvider::class,
        Webkul\DebugBar\Providers\DebugBarServiceProvider::class,
        Webkul\Marketing\Providers\MarketingServiceProvider::class,
        Webkul\Notification\Providers\NotificationServiceProvider::class,
        Webkul\Sitemap\Providers\SitemapServiceProvider::class,

         /**
         * PSW Package Service Providers.
         */
        PSW\Cinema\Providers\CinemaServiceProvider::class,
        PSW\Cinema\Film\Providers\FilmServiceProvider::class,
        PSW\Cinema\Shop\Providers\ShopServiceProvider::class,
        PSW\Cinema\Core\Providers\CoreServiceProvider::class,
        PSW\Cinema\Core\Providers\EnvValidatorServiceProvider::class,
        PSW\Cinema\Customer\Providers\CustomerServiceProvider::class,
    ],
   

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        'Captcha' => Webkul\Customer\Facades\Captcha::class,
        'Cart' => Webkul\Checkout\Facades\Cart::class,
        'Concord' => Konekt\Concord\Facades\Concord::class,
        'Core' => Webkul\Core\Facades\Core::class,
        'Datagrid' => Webkul\Ui\DataGrid\Facades\DataGrid::class,
        'DbView' => Flynsarmy\DbBladeCompiler\Facades\DbView::class,
        'Debugbar' => Barryvdh\Debugbar\Facades\Debugbar::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Helper'  => Konekt\Concord\Facades\Helper::class,
        'Image' => Intervention\Image\Facades\Image::class,
        'PDF' => Barryvdh\DomPDF\Facade::class,
        'ProductImage' => Webkul\Product\Facades\ProductImage::class,
        'MasterImage' => PSW\Cinema\Film\Facades\MasterImage::class,
        'ReleaseImage' => PSW\Cinema\Film\Facades\ReleaseImage::class,
        'ProductVideo' => Webkul\Product\Facades\ProductVideo::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ])->toArray(),
];
