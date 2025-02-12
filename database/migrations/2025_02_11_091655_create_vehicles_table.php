<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('vehicle_category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->string('merk')->nullable();
            $table->string('type')->nullable();
            $table->string('license_plate', 50)->unique();
            $table->string('registered_owner')->nullable();
            $table->string('assigned_user')->nullable();
            $table->date('tax_expiry_date');
            $table->date('plate_expiry_date');
            $table->boolean('remind_me')->default(1);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
