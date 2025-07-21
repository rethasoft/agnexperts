<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class File extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_ADMIN_DOCUMENTS = 'admin_documents';
    const TYPE_CUSTOMER_DOCUMENTS = 'customer_documents';
    const TYPE_INVOICE = 'invoice';

    protected $fillable = [
        'fileable_id',
        'fileable_type',
        'name',
        'path',
        'disk',
        'mime_type',
        'size',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
        'deleted_at' => 'datetime'
    ];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return route('files.show', $this);
    }

    public function isInvoice(): bool
    {
        return ($this->metadata['type'] ?? '') === self::TYPE_INVOICE;
    }

    public function isAdminDocument(): bool
    {
        return ($this->metadata['type'] ?? '') === self::TYPE_ADMIN_DOCUMENTS;
    }

    public function isCustomerDocument(): bool
    {
        return ($this->metadata['type'] ?? '') === self::TYPE_CUSTOMER_DOCUMENTS;
    }

    public function setMetadata(array $data)
    {
        $this->metadata = array_merge($this->metadata ?? [], $data);
        return $this;
    }

    public function getMetadata(string $key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    public function getFormattedSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function keuringen()
    {
        return $this->belongsTo(Keuringen::class, 'object_id', 'id');
    }

    public static function generatePath(string $type, int $modelId, string $filename): string
    {
        return sprintf(
            'inspections/%s/%d/%s',
            $type,
            $modelId,
            $filename
        );
    }

    /**
     * Get the hashed filename for secure access
     */
    protected function getHashedFilenameAttribute(): string
    {
        return Crypt::encryptString(json_encode([
            'type' => $this->metadata['type'] ?? 'documents',
            'path' => $this->path
        ]));
    }

    /**
     * Get the formatted filename for display
     */
    protected function getFormattedFilenameAttribute(): string
    {
        return basename($this->path);
    }

    /**
     * Get the secure URL for file access
     */
    protected function getSecureUrlAttribute(): string
    {
        return URL::signedRoute('files.show', ['hash' => $this->hashed_filename]);
    }
}
