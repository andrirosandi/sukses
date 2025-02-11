<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('license_plate', 50)->unique();
            $table->string('registered_owner')->nullable();
            $table->string('assigned_user')->nullable();
            $table->date('tax_expiry_date');
            $table->date('plate_expiry_date');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down() {
        Schema::dropIfExists('vehicles');
    }
};
