<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * LabSpecimen
 *
 * Model untuk data spesimen Lab Virtual (Modul 4 - Laboratorium Maya).
 * Setiap spesimen memiliki pasangan gambar makro-mikro, struktur sel
 * yang terlihat (disimpan JSON), dan integrasi tadabbur ayat Al-Quran.
 *
 * @property int    $id
 * @property string $name
 * @property string $icon
 * @property string|null $macro_image_path
 * @property string|null $micro_image_path
 * @property string $magnification
 * @property array  $structures        (JSON cast → PHP array)
 * @property string|null $quran_verse
 * @property string|null $quran_ref
 * @property string|null $reflection
 * @property bool   $is_active
 * @property int    $sort_order
 */
class LabSpecimen extends Model
{
    use HasFactory;

    protected $table = 'lab_specimens';

    protected $fillable = [
        'name',
        'icon',
        'macro_image_path',
        'micro_image_path',
        'magnification',
        'structures',
        'quran_verse',
        'quran_ref',
        'reflection',
        'is_active',
        'sort_order',
    ];

    /**
     * Accessor yang otomatis disertakan saat model di-serialize ke JSON/array.
     * Wajib didaftarkan di sini agar @json($specimens) di Blade menyertakan
     * nilai URL gambar yang sudah diproses (bukan hanya path mentah dari DB).
     */
    protected $appends = [
        'macro_image_url',
        'micro_image_url',
    ];

    /**
     * Cast kolom JSON ke native PHP array secara otomatis.
     * Cast boolean agar tidak perlu konversi manual.
     */
    protected $casts = [
        'structures' => 'array',
        'is_active'  => 'boolean',
    ];

    // ====================================================================
    // SCOPES
    // ====================================================================

    /**
     * Scope: hanya spesimen aktif, urut berdasarkan sort_order.
     * Digunakan di VirtualLabController (halaman siswa).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // ====================================================================
    // ACCESSORS
    // ====================================================================

    /**
     * URL publik gambar Makro.
     * Mendukung 2 sumber: storage (upload guru) & public/images/lab (legacy seeder).
     */
    public function getMacroImageUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->macro_image_path, 'macro');
    }

    /**
     * URL publik gambar Mikro.
     */
    public function getMicroImageUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->micro_image_path, 'micro');
    }

    /**
     * Helper: tentukan URL gambar dari path yang disimpan.
     * Path yang diawali 'lab-specimens/' → dari storage (upload guru).
     * Path yang diawali '/images/'       → dari public langsung (legacy/seeder).
     */
    private function resolveImageUrl(?string $path, string $type): string
    {
        if (!$path) {
            return asset("images/lab/placeholder-{$type}.png");
        }

        // Legacy path dari seeder (diawali /images/ → langsung dari public/)
        if (str_starts_with($path, '/images/')) {
            return asset(ltrim($path, '/'));
        }

        // Upload dari admin: disimpan di storage/app/public/lab-specimens/
        $fullPath = public_path('storage/' . $path);
        if (file_exists($fullPath)) {
            return asset('storage/' . $path);
        }

        return asset("images/lab/placeholder-{$type}.png");
    }

    /**
     * Accessor: kembalikan structures sebagai array yang selalu valid (tidak null).
     */
    public function getStructuresArrayAttribute(): array
    {
        return $this->structures ?? [];
    }
}
