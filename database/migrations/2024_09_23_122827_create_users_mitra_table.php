<?php

use App\Helpers\Database;
use App\Models\User;
use App\Models\UserMitra;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected Model $model;

    protected Model $modelUser;

    public function __construct()
    {
        $this->model = new UserMitra();

        $this->modelUser = new User();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->model->getConnectionName())->create($this->model->getTable(), function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();
            $table->string('promotion_code', 50);
            $table->double('balances')->default(0)->unsigned();
            $table->enum('buyer_benefit_type', ['percent', 'deduction'])->default('percent');
            $table->integer('buyer_benefit_value')->unsigned();
            $table->enum('user_benefit_type', ['percent', 'deduction'])->default('percent');
            $table->integer('user_benefit_value')->unsigned();
            $table->string('company_name', 100);
            $table->string('company_email', 200);
            $table->string('company_phone', 20);
            $table->string('company_address', 200);
            $table->text('company_logo');

            $table
                ->foreign('user_id')
                ->references('id')
                ->on(Database::getFullTableName($this->modelUser))
                ->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->model->getConnectionName())->dropIfExists($this->model->getTable());
    }
};
