<?php

use Illuminate\Support\ServiceProvider;

test('it registers config and migration publish groups', function () {
    $configPaths = ServiceProvider::pathsToPublish(
        PHPDominicana\Reactify\ReactifyServiceProvider::class,
        'reactify-config'
    );

    $migrationPaths = ServiceProvider::pathsToPublish(
        PHPDominicana\Reactify\ReactifyServiceProvider::class,
        'reactify-migrations'
    );

    expect($configPaths)->not->toBeEmpty()
        ->and(array_key_first($configPaths))->toEndWith('/config/reactify.php')
        ->and(array_values($configPaths)[0])->toEndWith('/config/reactify.php')
        ->and($migrationPaths)->not->toBeEmpty()
        ->and(array_key_first($migrationPaths))->toEndWith('/database/migrations/create_reactify_table.php')
        ->and(array_values($migrationPaths)[0])->toContain('/database/migrations/')
        ->toContain('create_reactify_table.php');
});
