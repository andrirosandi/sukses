<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_category_id')->constrained('contact_categories');
            $table->string('account', 255);
            $table->string('name', 255);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    public function down() {
        Schema::dropIfExists('contacts');
    }
};
