<?php

namespace App\Models;

use Database\Factories\PageViewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['page', 'ip_hash', 'session_id', 'user_agent', 'referrer', 'country', 'viewed_at'])]
class PageView extends Model
{
    /** @use HasFactory<PageViewFactory> */
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function scopeForPeriod(Builder $query, int $days): Builder
    {
        return $query->where('viewed_at', '>=', now()->subDays($days));
    }
}
