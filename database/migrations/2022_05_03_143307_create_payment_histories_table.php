<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->unsignedBigInteger('client_id')->nullable()->index();
            $table->unsignedBigInteger('currency_id')->nullable()->index();
            $table->date('invoice_date')->nullable()->index();
            $table->date('due_date')->nullable();
            $table->date('billing_regular_date')->nullable();
            $table->double('amount')->nullable()->index();
            $table->double('expected_amount')->nullable()->index();
            $table->double('amount_in_doller')->nullable()->index();
            $table->string('invoice_no')->nullable()->index();
            $table->string('bank_account')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('payment_platform')->nullable();
            $table->string('invoice_url')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('invoice_description')->nullable();
            $table->text('bank_details')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_histories');
    }
}
