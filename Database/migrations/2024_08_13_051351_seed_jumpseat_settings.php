<?php

use App\Contracts\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class SeedJumpseatSettings
 */
class SeedJumpseatSettings extends Migration
{
    /**
     * Run thce migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\Models\Setting::firstOrNew(['id' => 'ch_jumpseat_price']);
        $setting->id = 'ch_jumpseat_price';
        $setting->offset = 9000;
        $setting->order = 9100;
        $setting->key = 'ch_jupmpseat.price';
        $setting->name = 'User Price';
        $setting->value = 1000;
        $setting->default = 1000;
        $setting->group = 'chjumpseat';
        $setting->type = 'int';
        $setting->description = 'The price to set for a user created jumpseat with the decimal suppressed if the currency applies (e.g. $1000.00 would be entered as "100000")';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
