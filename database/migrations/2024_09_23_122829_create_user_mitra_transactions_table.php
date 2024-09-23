<?php

use App\Helpers\Database;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserMitraTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected Model $model;

    protected Model $modelUser;

    protected Model $modelPayment;

    public function __construct()
    {
        $this->model = new UserMitraTransaction();

        $this->modelUser = new User();

        $this->modelPayment = new Payment();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->model->getConnectionName())->create($this->model->getTable(), function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id_mitra')->unsigned();
            $table->bigInteger('user_id_buyer')->unsigned()->nullable();
            $table->uuid('transaction_id')->nullable();
            $table->bigInteger('total_transaction')->unsigned();
            $table->bigInteger('total_income')->unsigned();
            $table->text('promotion_data')->nullable();

            $table
                ->foreign('user_id_mitra')
                ->references('id')
                ->on(Database::getFullTableName($this->modelUser))
                ->onDelete('CASCADE');
            $table
                ->foreign('user_id_buyer')
                ->references('id')
                ->on(Database::getFullTableName($this->modelUser))
                ->onDelete('SET NULL');
            $table
                ->foreign('transaction_id')
                ->references('id')
                ->on(Database::getFullTableName($this->modelPayment))
                ->onDelete('SET NULL');

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
