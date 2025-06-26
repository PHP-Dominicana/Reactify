<?php

namespace PHPDominicana\Reactify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReactionCounter extends Model
{
    protected $fillable = ['reactable_id', 'reactify_table_type', 'count'];

    protected $table = 'reactify_react_counters';

    public function reactionable()
    {
        return $this->morphTo();
    }

    /**
     * Delete all counts of the given model, and recount them and insert new counts
     *
     * @param $modelClass
     */
    public static function rebuild($modelClass)
    {
        if (empty($modelClass)) {
            throw new \Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
        }

        $builder = ReactifyTable::query()
            ->select(DB::raw('count(*) as count, reactionable_type, reactable_id'))
            ->where('reactionable_type', $modelClass)
            ->groupBy('reactable_id');

        $results = $builder->get();
        $inserts = $results->toArray();

        DB::table((new static)->table)->insert($inserts);
    }
}
