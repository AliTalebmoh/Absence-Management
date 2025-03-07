<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, make subject_id and class_id nullable
        Schema::table('absences', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->change();
            $table->foreignId('class_id')->nullable()->change();
        });

        // Then drop the foreign keys if they exist
        Schema::table('absences', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['class_id']);
        });

        // Finally drop the columns
        Schema::table('absences', function (Blueprint $table) {
            $table->dropColumn(['subject_id', 'class_id']);
        });

        // Make sure we have the correct columns
        if (!Schema::hasColumn('absences', 'period')) {
            $table->string('period')->default('morning');
        }
        if (!Schema::hasColumn('absences', 'hours_absent')) {
            $table->decimal('hours_absent', 3, 1)->default(4);
        }
    }

    public function down()
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->foreignId('class_id')->nullable()->constrained('classes');
        });
    }
}; 