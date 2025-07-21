<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'description', 'parent_id'];

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
    // Folder.php
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id')->with('children');
    }

    // Recursive olarak alt klasörlerin ID'lerini getirir
    public function getAllChildFolderIds()
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllChildFolderIds());
        }

        return $ids;
    }

    public function buildBreadcrumb()
    {
        $names = [];
        $folder = $this;

        while ($folder) {
            array_unshift($names, $folder->name);
            $folder = $folder->parent;
        }

        return implode(' > ', $names);
    }
}
