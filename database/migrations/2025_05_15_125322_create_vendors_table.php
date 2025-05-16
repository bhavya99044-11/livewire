<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Status;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('domain_id')->nullable()->constrained('domains')->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->integer('contact_number')->unique()->nullable();
            $table->string('password');
            $table->string('shop_name');
            $table->string('logo_url');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->integer('pincode');
            $table->string('status')->default(Status::INACTIVE->value);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_shop')->default(0)->comment('Shop status');
            $table->decimal('lattitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->decimal('packaging_processing_charges')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->unsignedBigInteger('deleted_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
