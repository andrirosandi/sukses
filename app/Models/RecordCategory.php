<?php
namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class RecordCategory extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'created_by', 'updated_by'];

    public static function getTableColumns(?int $recordCategoryId,?array $type = []): array
    {
        
        if (!$recordCategoryId) {
            Log::warning("getTableColumns: record_category_id is null");
            return [];
        }

        // Ambil record category berdasarkan ID
        $recordCategory = self::find($recordCategoryId);
        if (!$recordCategory || !$recordCategory->name) {
            Log::warning("getTableColumns: RecordCategory tidak ditemukan untuk ID: {$recordCategoryId}");
            return [];
        }

        // Cari model berdasarkan nama kategori (pastikan nama sesuai dengan class di App\Models)
        $modelClassName = "App\\Models\\" . ucfirst($recordCategory->name);
        if (!class_exists($modelClassName)) {
            Log::warning("getTableColumns: Model class tidak ditemukan: {$modelClassName}");
            return [];
        }

        // Buat instance model
        $modelInstance = app($modelClassName);
        if (!$modelInstance instanceof Model) {
            Log::error("getTableColumns: Model bukan instance Eloquent: {$modelClassName}");
            return [];
        }

        try {
            $tableName = $modelInstance->getTable();
            if (!Schema::hasTable($tableName)) {
                Log::warning("getTableColumns: Tabel tidak ditemukan di database: {$tableName}");
                return [];
            }

            // Ambil daftar kolom tabel, kecuali id, timestamps, dan deleted_at
            $excludedColumns = ['id', 'created_at', 'updated_at', 'deleted_at'];
            $columns = array_diff(Schema::getColumnListing($tableName), $excludedColumns);

            // dump(Schema::getColumnListing($tableName));
            // Konversi kolom yang berakhiran "_id" menjadi relasi
            if (empty($type)) {
                # code...
                return collect($columns)
                    ->mapWithKeys(function ($col) use ($modelInstance) {
                    if (str_ends_with($col, '_id')) {
                        $relationName = str_replace('_id', '', $col);
                        $relationMethod = \Illuminate\Support\Str::camel($relationName);
                        
                        return [$col => method_exists($modelInstance, $relationMethod) ? "{$relationMethod}.name" : $col];
                    }
                    return [$col => $col];
                })->toArray();
            }
            else{
                return collect($columns)
                    // ->reject(fn ($col) => in_array($col, $excludedColumns)) // Hilangkan kolom yang tidak relevan
                    ->filter(fn ($col) => in_array(Schema::getColumnType($tableName, $col), $type)) // Hanya ambil date/datetime
                    ->mapWithKeys(fn ($col) => [$col => ucfirst(str_replace('_', ' ', $col))]) // Format label dropdown
                    ->toArray();
            }
        } catch (\Exception $e) {
            Log::error("getTableColumns: Gagal mengambil kolom tabel {$tableName}", ['error' => $e->getMessage()]);
            return [];
        }
    }

    
}
