<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // إزالة الأعمدة الخاصة بالعنوان
            $table->dropColumn('street_name');
            $table->dropColumn('building_number');
            $table->dropColumn('floor_number');
            $table->dropColumn('apartment_number');

            // إضافة الحقل الجديد لربط العنوان
            $table->foreignId('address_id')->after('customer_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // إعادة الأعمدة في حالة rollback
            $table->string('street_name');
            $table->string('building_number');
            $table->string('floor_number');
            $table->string('apartment_number');

            // حذف العمود الجديد
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');
        });
    }
};
