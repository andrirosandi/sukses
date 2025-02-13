<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reminder_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->constrained('reminders')->onDelete('cascade');
            $table->foreignId('contact_id')->constrained('contacts')->onDelete('cascade');
            $table->timestamps();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_recipients');
    }
};