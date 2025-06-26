<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reactify_table', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->morphs('reactionable');
            $table->string('type'); // e.g., 'like', 'love', 'haha', etc.
            $table->timestamps();
            $table->unique(['user_id', 'reactable_id', 'reactify_table_type'], 'reactify_table_user_reactable_type_unique');
        });

        Schema::create('reactify_react_counters', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->morphs('reactionable');
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['reactionable_id', 'reactionable_type'], 'reactionable_counts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactify_table');
        Schema::dropIfExists('reactify_react_counters');
    }
};
