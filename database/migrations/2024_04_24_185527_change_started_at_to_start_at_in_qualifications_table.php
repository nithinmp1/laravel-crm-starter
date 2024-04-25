<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qualifications', function (Blueprint $table) {
            $table->renameColumn('started_at', 'start_at');
            // $table->dateTime('start_at')->change();
            $table->renameColumn('completed_at', 'finish_at');
            // $table->dateTime('finish_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qualifications', function (Blueprint $table) {
            $table->renameColumn('start_at', 'started_at');
            $table->timestamp('start_at')->change();
            $table->renameColumn('finish_at', 'completed_at');
            $table->timestamp('finish_at')->change();
        });
    }
};
