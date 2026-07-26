<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'slug',
        'description',
        'youtube_id',
        'history_founder',
        'quran_verse',
        'image_path',
        'sort_order',
    ];

    /**
     * Konstanta untuk nilai ENUM kolom type — hindari magic string di controller.
     */
    const TYPE_ANIMAL     = 'animal';
    const TYPE_PLANT      = 'plant';
    const TYPE_MICROSCOPE = 'microscope';

    /**
     * Scope helper: ambil materi berdasarkan tipe.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: urutkan berdasarkan sort_order untuk tampilan diagram.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Relasi: satu organel/materi bisa memiliki banyak hotspot koordinat klik.
     * Eager-load dengan: Material::with('hotspots')->...
     */
    public function hotspots(): HasMany
    {
        return $this->hasMany(MaterialHotspot::class);
    }

    /**
     * Accessor: kembalikan URL gambar WebP yang sudah terkompresi, atau fallback placeholder.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && file_exists(public_path('storage/' . $this->image_path))) {
            return asset('storage/' . $this->image_path);
        }
        return asset('images/placeholder-cell.png');
    }
}
