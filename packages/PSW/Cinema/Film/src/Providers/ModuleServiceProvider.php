<?php

namespace PSW\Cinema\Film\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \PSW\Cinema\Film\Models\MasterPage::class,
        \PSW\Cinema\Film\Models\ReleasesPage::class,
        \PSW\Cinema\Film\Models\GeneriPage::class,
        \PSW\Cinema\Film\Models\SubgeneriPage::class,
        \PSW\Cinema\Film\Models\ActoryPage::class,
        \PSW\Cinema\Film\Models\SceneggiatoriPage::class,
        \PSW\Cinema\Film\Models\RegistiPage::class,
        \PSW\Cinema\Film\Models\CompositoriPage::class,
        \PSW\Cinema\Film\Models\CasaproduzioniPage::class,
        \PSW\Cinema\Film\Models\LinguePage::class,
        \PSW\Cinema\Film\Models\ReleaseImage::class,
        \PSW\Cinema\Film\Models\MasterImage::class,
    ];
}