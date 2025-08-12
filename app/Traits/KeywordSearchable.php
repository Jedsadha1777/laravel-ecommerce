<?php

namespace App\Traits;

trait KeywordSearchable
{
    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) {
            return $query;
        }

        $searchableFields = $this->searchableFields ?? ['name'];
        
        return $query->where(function ($q) use ($keyword, $searchableFields) {
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'like', "%{$keyword}%");
            }
        });
    }
}