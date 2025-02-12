<?php
// database/migrations/YYYY_MM_DD_create_reminders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('reminder_message')->nullable();
            $table->foreignId('record_category_id')->constrained('record_categories');
            $table->boolean('enabled')->default(1);
            $table->string('repeat_every', 50);
            $table->string('on_days');
            $table->time('on_time');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
