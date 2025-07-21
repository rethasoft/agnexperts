<?php

namespace App\Domain\DocumentManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'name',
        'file_path',
        'file_type',
        'document_type',
        'description',
        'issue_date',
        'expiry_date',
        'is_verified',
        'verified_by',
        'verified_at',
        'meta', // Meta veriler için yeni alan
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'meta' => 'array', // Meta verileri JSON olarak sakla ve array olarak kullan
    ];

    /**
     * Get the parent documentable model.
     */
    public function documentable()
    {
        return $this->morphTo();
    }

    /**
     * Get a specific meta value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getMeta(string $key, $default = null)
    {
        return data_get($this->meta, $key, $default);
    }

    /**
     * Set a specific meta value.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setMeta(string $key, $value)
    {
        $meta = $this->meta ?? [];
        data_set($meta, $key, $value);
        $this->meta = $meta;

        return $this;
    }
}
