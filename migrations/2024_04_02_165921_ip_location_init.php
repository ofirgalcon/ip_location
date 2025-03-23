<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class IpLocationInit extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('ip_location', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number');
            $table->string('ip')->nullable();
            $table->string('hostname')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->string('organization')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('timezone')->nullable();

            $table->unique('serial_number');
            $table->index('ip');
            $table->index('hostname');
            $table->index('city');
            $table->index('region');
            $table->index('country');
            $table->index('location');
            $table->index('organization');
            $table->index('postal_code');
            $table->index('timezone');

        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('ip_location');
    }
}
