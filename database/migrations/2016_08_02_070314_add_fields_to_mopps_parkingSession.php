<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMoppsParkingSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mopps_parkingSession', function (Blueprint $table) {
            $table->timestamps();
            $table->softDeletes();
            $table->renameColumn('location_id', 'parking_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mopps_parkingSession', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('deleted_at');
            $table->renameColumn('parking_id', 'location_id');
        });
    }
}
