<?php

use App\Contracts\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateJumpseatRequestsTable
 */
class CreateJumpseatRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ch_jumpseat_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('airport_id');
            $table->integer('type')->default(0); // Automatic or Manual
            $table->string('request_reason')->nullable();
            $table->string('deny_reason')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ch_jumpseat_requests');
    }
}
