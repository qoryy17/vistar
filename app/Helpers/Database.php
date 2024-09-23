<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class Database
{
    public static function getFullTableName(Model $model): string
    {
        $databaseName = $model->getConnection()->getDatabaseName();
        $tableName = $model->getTable();

        return "$databaseName.$tableName";
    }
}
