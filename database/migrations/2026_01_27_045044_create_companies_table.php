<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_logo')->nullable();
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('contact_number', 20);
            $table->string('certificate_of_corporation');
            $table->string('certificate_of_registration');
            $table->string('mayors_permit');
            $table->string('barangay_clearance');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
}
