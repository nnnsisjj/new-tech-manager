<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('schedule', function (Blueprint $table) {
        $table->id();
        $table->foreignId('group_id')->constrained();
        $table->foreignId('subject_id')->constrained();
        $table->foreignId('teacher_id')->constrained();
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->string('classroom', 50);
        $table->timestamps();
    });
}
};
