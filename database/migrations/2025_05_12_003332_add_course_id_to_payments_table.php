<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Проверяем, существует ли столбец, прежде чем добавлять
            if (!Schema::hasColumn('payments', 'course_id')) {
                // Если course_id должен быть обязательным
                $table->foreignId('course_id')->constrained()->after('student_id');
                
                // ИЛИ если он может быть nullable
                // $table->foreignId('course_id')->nullable()->constrained()->after('student_id');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Проверяем существование столбца перед удалением
            if (Schema::hasColumn('payments', 'course_id')) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }
        });
    }
};