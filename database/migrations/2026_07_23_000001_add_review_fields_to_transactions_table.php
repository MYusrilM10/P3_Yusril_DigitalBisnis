<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('review_email_sent_at')->nullable()->after('status');
            $table->string('review_token', 64)->nullable()->unique()->after('review_email_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['review_email_sent_at', 'review_token']);
        });
    }
};
