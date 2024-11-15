<?php

namespace App\Helpers;

use App\Models\Sertikom\VisitorProdukModel;

class RecordVisitor
{
    public static function saveRecord($data = false)
    {
        // Check if record exist
        $checkRecord = VisitorProdukModel::where('ref_produk_id', $data['ref_produk_id'])->where('ip_address', $_SERVER['REMOTE_ADDR'])->where('tanggal', date('Y-m-d'))->first();
        if (!$checkRecord) {
            $save = VisitorProdukModel::create($data);
            if (!$save) {
                return false;
            }
        }
        return true;
    }
}
