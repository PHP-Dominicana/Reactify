<?php

namespace PHPDominicana\Reactify\Models;

use Illuminate\Database\Eloquent\Model;

class ReactifyTable extends Model
{
    protected $fillable = ['user_id', 'type'];

    protected $table = 'reactify_table';

    public function likeable()
    {
        return $this->morphTo();
    }
}
