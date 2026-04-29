<?php

namespace App\Models;

use Database\Factories\ImpactAreaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'tagline', 'bullets', 'sort_order'])]
class ImpactArea extends Model
{
    /** @use HasFactory<ImpactAreaFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'bullets' => 'array',
        ];
    }
}
