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
            $table->unsignedBigInteger('user_owner_id')->nullable()->after('completed_at');
            $table->foreign('user_owner_id')->references('id')->on('users');
            $table->timestamp('updated_at')->nullable()->after('user_owner_id');
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
            $table->dropForeign(['user_owner_id']);
            $table->dropColumn('user_owner_id');
            $table->dropColumn('updated_at');
        });
    }
};
