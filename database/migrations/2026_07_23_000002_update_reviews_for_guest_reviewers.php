<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Allow guest reviewers (no login required)
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'event_id']);
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Snapshot customer data (in case of guest reviewer)
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_email')->nullable()->after('customer_name');

            // One review per transaction
            $table->unique('transaction_id', 'reviews_transaction_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('reviews_transaction_id_unique');
            $table->dropForeign(['user_id']);
            $table->dropColumn(['customer_name', 'customer_email']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'event_id']);
        });
    }
};
