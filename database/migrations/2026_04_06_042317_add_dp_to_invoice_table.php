<?php
// database/migrations/add_dp_to_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('dp_amount', 15, 2)->default(0)->after('discount');
            $table->decimal('remaining_amount', 15, 2)->default(0)->after('dp_amount');
            $table->enum('dp_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('remaining_amount');
            $table->date('dp_due_date')->nullable()->after('dp_status');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['dp_amount', 'remaining_amount', 'dp_status', 'dp_due_date']);
        });
    }
};