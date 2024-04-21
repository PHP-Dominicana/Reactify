<?php

namespace PHPDominicana\Reactify\Providers;

use Illuminate\Support\ServiceProvider;
use PHPDominicana\Reactify\Models\ReactifyTable as Reaction;

class ReactifyServicesProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ReactifyTable', function () {
            return new Reaction();
        });
    }
}
