<?php

use App\Enums\AdminSizeTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sub_products', function (Blueprint $table) {
            $table->dropColumn('size_type');
        });

        Schema::table('sub_products', function (Blueprint $table) {
            $table->enum('size_type', [AdminSizeTypes::values()])->default(0)->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_products', function (Blueprint $table) {
            // $table->dropColumn('size_type');
            // $table->string('size_type')->default('0');
        });
    }
};
