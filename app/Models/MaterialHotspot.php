<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model MaterialHotspot
 *
 * Menyimpan satu koordinat klik organel pada diagram sel interaktif.
 * Relasi: BelongsTo Material (cascade delete otomatis via migrasi FK).
 */
class MaterialHotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'left_percent',
        'top_percent',
        'bg_color',
        'delay',
    ];

    /**
     * Hotspot ini milik satu organel/materi tertentu.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
