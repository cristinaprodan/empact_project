<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('versions_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version');
            $table->timestamps();
        });

        DB::table('versions_tables')->insert(
            array(
                ['version' => "2.5.0-dev.1"],
                ['version' => "2.4.2-5354"],
                ['version' => "2.4.2-test.675"],
                ['version' => "2.4.1-integration.1"],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('versions_tables');
    }
};
