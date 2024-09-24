<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected Model $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->model->getConnectionName())->table($this->model->getTable(), function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->model->getConnectionName())->table($this->model->getTable(), function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
        });
    }
};
