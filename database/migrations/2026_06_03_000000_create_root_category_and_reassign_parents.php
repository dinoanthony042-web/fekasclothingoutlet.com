<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateRootCategoryAndReassignParents extends Migration
{
    public function up()
    {
        // Create backup table (DDL should not be wrapped in DB transactions on some drivers)
        if (!Schema::hasTable('category_parent_backups')) {
            Schema::create('category_parent_backups', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->primary();
                $table->unsignedBigInteger('old_parent_id')->nullable();
                $table->text('payload')->nullable();
            });
        }

        // Backup current categories (non-transactional)
        // Ensure backup table is empty (safe for re-runs)
        DB::table('category_parent_backups')->truncate();

        $categories = DB::table('categories')->get();

        foreach ($categories as $cat) {
            DB::table('category_parent_backups')->insert([
                'category_id' => $cat->id,
                'old_parent_id' => $cat->parent_id,
                'payload' => json_encode((array) $cat),
            ]);
        }

        $rootCount = DB::table('categories')->whereNull('parent_id')->count();

        // Only perform data changes transactionally
        if ($rootCount > 1) {
            DB::transaction(function () {
                // Prefer an existing 'Fashion' category (case-insensitive) and promote it to root if necessary
                $fashion = DB::table('categories')
                    ->whereRaw('LOWER(name) = ?', [Str::lower('Fashion')])
                    ->first();

                if ($fashion) {
                    if ($fashion->parent_id !== null) {
                        DB::table('categories')->where('id', $fashion->id)->update(['parent_id' => null]);
                    }
                    $rootId = $fashion->id;
                    DB::table('category_parent_backups')->insert([
                        'category_id' => 0,
                        'old_parent_id' => null,
                        'payload' => json_encode(['created_root_id' => null, 'used_existing' => $rootId]),
                    ]);
                } else {
                    // create new root 'Fashion'
                    $now = now();
                    $newId = DB::table('categories')->insertGetId([
                        'name' => 'Fashion',
                        'slug' => Str::slug('Fashion'),
                        'description' => 'Root category created by migration',
                        'parent_id' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('category_parent_backups')->insert([
                        'category_id' => 0,
                        'old_parent_id' => null,
                        'payload' => json_encode(['created_root_id' => $newId]),
                    ]);

                    $rootId = $newId;
                }

                // Reassign other top-level categories under the root. Merge duplicates by name.
                $roots = DB::table('categories')->whereNull('parent_id')->get();

                foreach ($roots as $r) {
                    if ($r->id == $rootId) {
                        continue;
                    }

                    $existing = DB::table('categories')
                        ->where('parent_id', $rootId)
                        ->whereRaw('LOWER(name) = ?', [Str::lower($r->name)])
                        ->first();

                    if ($existing) {
                        // move children of the duplicate into the existing node
                        DB::table('categories')->where('parent_id', $r->id)->update(['parent_id' => $existing->id]);
                        // remove the duplicate top-level category (we backed up everything)
                        DB::table('categories')->where('id', $r->id)->delete();
                    } else {
                        // simply set this top-level category as a child of the root
                        DB::table('categories')->where('id', $r->id)->update(['parent_id' => $rootId]);
                    }
                }
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('category_parent_backups')) {
            return;
        }

        $backups = DB::table('category_parent_backups')->get();

        // First, handle the special marker row (category_id = 0) and then restore data inside a transaction
        $marker = $backups->where('category_id', 0)->first();

        DB::transaction(function () use ($backups, $marker) {
            if ($marker) {
                $meta = json_decode($marker->payload, true);
                if (!empty($meta['created_root_id'])) {
                    DB::table('categories')->where('id', $meta['created_root_id'])->delete();
                }
            }

            foreach ($backups as $row) {
                if ($row->category_id == 0) {
                    continue;
                }

                $payload = json_decode($row->payload, true);
                $exists = DB::table('categories')->where('id', $row->category_id)->first();

                if ($exists) {
                    DB::table('categories')->where('id', $row->category_id)->update(['parent_id' => $row->old_parent_id]);
                } else {
                    if (is_array($payload) && isset($payload['id'])) {
                        DB::table('categories')->insert($payload);
                    } else {
                        DB::table('categories')->insert([
                            'id' => $row->category_id,
                            'parent_id' => $row->old_parent_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });

        // Drop backup table outside transaction
        Schema::dropIfExists('category_parent_backups');
    }
}
