<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Add slug column if not exists
        if (! Schema::hasColumn('categories', 'slug')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        // Backfill slug for existing rows based on name
        $rows = DB::table('categories')->whereNull('slug')->orWhere('slug', '')->get();
        foreach ($rows as $row) {
            $base = Str::slug($row->name);
            $slug = $base;
            $i = 1;
            while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base . '-' . (++$i);
            }
            DB::table('categories')->where('id', $row->id)->update(['slug' => $slug]);
        }

        // Make slug unique (skip if already exists)
        $hasUnique = collect(DB::select("SHOW INDEXES FROM categories WHERE Key_name = 'categories_slug_unique'"))->isNotEmpty();
        if (! $hasUnique) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
