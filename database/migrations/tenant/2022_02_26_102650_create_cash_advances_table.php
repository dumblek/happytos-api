<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_type');
            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedInteger('payment_id')->nullable();
            $table->decimal('amount', 65, 30);
            $table->decimal('amount_remaining', 65, 30);
            $table->datetime('archived_at')->nullable();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('restrict');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_advances');
    }
}
