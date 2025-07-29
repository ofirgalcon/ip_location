<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class AddLastUpdateToIpLocation extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table('ip_location', function (Blueprint $table) {
            $table->bigInteger('last_update')->nullable()->after('timezone');
            $table->index('last_update');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table('ip_location', function (Blueprint $table) {
            $table->dropColumn('last_update');
        });
    }
} 