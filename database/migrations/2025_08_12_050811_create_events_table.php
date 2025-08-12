<?php

use App\Enums\StatusEventEnum;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 255)->unique()->nullable();
            $table->string('cover_image')->nullable();
            $table->text('deskripsi' )->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('location' )->nullable();
            $table->string('status')->default(StatusEventEnum::Ongoing->value);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('capacity')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
