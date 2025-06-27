<?php

namespace PHPDominicana\Reactify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use PHPDominicana\Reactify\Enums\Reaction;

class ReactifyTable extends Model
{
    protected $fillable = ['user_id', 'type'];

    protected $table = 'reactify_table';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => Reaction::class,
        ];
    }

    public function reactionable()
    {
        return $this->morphTo();
    }

    public function reaction(): MorphOne
    {
        return $this->morphOne(ReactionCounter::class, 'reactionable');
    }
}
