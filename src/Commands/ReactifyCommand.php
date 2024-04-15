<?php

namespace PHPDominicana\Reactify\Commands;

use Illuminate\Console\Command;

class ReactifyCommand extends Command
{
    public $signature = 'reactify';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
