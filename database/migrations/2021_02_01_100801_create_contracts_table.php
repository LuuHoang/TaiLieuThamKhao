<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sample_contract_id');
            $table->unsignedBigInteger('company_id');
            $table->string('represent_company_hire');
            $table->string('phone_company_hire');
            $table->integer('gender_hire');
            $table->text('name_company_rental');
            $table->text('address_company_rental');
            $table->string('represent_company_rental');
            $table->integer('gender_rental')->nullable();
            $table->string('phone_number_rental');
            $table->unsignedBigInteger('service_package_id');
            $table->integer('type_service_package');
            $table->integer('extend_package_id')->nullable();
            $table->double('tax');
            $table->double('total_price');
            $table->integer('payment_status');
            $table->date('effective_date');
            $table->date('end_date');
            $table->integer('cancellation_notice_deadline');
            $table->double('deposit_money')->nullable();
            $table->integer('payment_term_all');
            $table->string('employee_represent');
            $table->integer('contract_status');
            $table->date('date_signed');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
