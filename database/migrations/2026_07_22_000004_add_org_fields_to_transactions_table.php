<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Idempotent: skip jika kolom sudah ada
            if (!Schema::hasColumn('transactions', 'organization_id')) {
                $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->nullOnDelete();
            }
            if (!Schema::hasColumn('transactions', 'platform_fee')) {
                $table->decimal('platform_fee', 12, 2)->default(0)->after('total_price');
            }
            if (!Schema::hasColumn('transactions', 'net_income')) {
                $table->decimal('net_income', 12, 2)->default(0)->after('platform_fee');
            }
            if (!Schema::hasColumn('transactions', 'payout_id')) {
                $table->foreignId('payout_id')->nullable()->after('net_income')->constrained('payouts')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('transactions', 'payout_id')) $cols[] = 'payout_id';
            if (Schema::hasColumn('transactions', 'net_income')) $cols[] = 'net_income';
            if (Schema::hasColumn('transactions', 'platform_fee')) $cols[] = 'platform_fee';
            if (Schema::hasColumn('transactions', 'organization_id')) $cols[] = 'organization_id';

            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
