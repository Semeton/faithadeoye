<?php

namespace App\Models;

use Database\Factories\CareerMilestoneFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['period', 'role', 'company', 'sort_order'])]
class CareerMilestone extends Model
{
    /** @use HasFactory<CareerMilestoneFactory> */
    use HasFactory;
}
