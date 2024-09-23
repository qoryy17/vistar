<?php

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected Model $model;

    public function __construct()
    {
        $this->model = new Payment();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->model->getConnectionName())->table($this->model->getTable(), function (Blueprint $table) {
            $table->double('subtotal')->unsigned()->after('transaksi_id');
            $table->string('promo_type', 50)->after('subtotal')->nullable();
            $table->string('promo_code', 50)->after('promo_type')->nullable();
            $table->string('promo_data', 200)->after('promo_code')->nullable();
            $table->double('discount')->unsigned()->after('promo_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->model->getConnectionName())->table($this->model->getTable(), function (Blueprint $table) {
            $table->dropColumn('subtotal');
            $table->dropColumn('promo_type');
            $table->dropColumn('promo_code');
            $table->dropColumn('promo_data');
            $table->dropColumn('discount');
        });
    }
};
