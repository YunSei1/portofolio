<?php

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
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('project_url', 'demo_url');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->text('problem')->nullable()->after('description');
            $table->text('goal')->nullable()->after('problem');
            $table->string('technologies')->nullable()->after('goal');
            $table->string('role')->nullable()->after('technologies');
            $table->text('features')->nullable()->after('role');
            $table->text('documentation')->nullable()->after('features');
            $table->longText('detail')->nullable()->after('documentation');
            $table->string('source_url')->nullable()->after('demo_url');
            $table->text('feedback')->nullable()->after('detail');
            $table->string('feedback_author')->nullable()->after('feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'problem',
                'goal',
                'technologies',
                'role',
                'features',
                'documentation',
                'detail',
                'source_url',
                'feedback',
                'feedback_author',
            ]);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('demo_url', 'project_url');
        });
    }
};
