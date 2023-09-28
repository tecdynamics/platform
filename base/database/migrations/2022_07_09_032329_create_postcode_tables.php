<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('postal_codes')) {
            Schema::create('postal_codes', function (Blueprint $table) {
                $table->id();
                $table->integer('country_id');
                $table->integer('state_id');
                $table->string('postcode')->index();
                $table->unique(['postcode', 'country_id'], 'postcodeid');
            });
        }
        Schema::table('ec_shipping_rule_items', function (Blueprint $table) {
            if (Schema::hasColumn('ec_shipping_rule_items', 'name')) {
                $table->string('name',192)->nullable()->after('country');
            }
            if (Schema::hasColumn('ec_shipping_rule_items', 'state')) {
                $table->text('state')->nullable()->after('name')->change();
            }
            if (!Schema::hasColumn('ec_shipping_rule_items', 'postcodes')) {
                $table->text('postcodes')->nullable()->after('city');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postal_codes');

        Schema::table('ec_shipping_rule_items', function (Blueprint $table) {
            if (Schema::hasColumn('ec_shipping_rule_items', 'postcodes')) {
                $table->dropColumn('postcodes');
            }
        });
    }
};
