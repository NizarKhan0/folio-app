<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $role
 * @property string $company
 * @property string|null $location
 * @property string $period
 * @property string $description
 * @property array<int, string>|null $tech_stack
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'role',
    'company',
    'location',
    'period',
    'description',
    'tech_stack',
    'sort_order',
])]
class Experience extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tech_stack' => 'array',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the uppercase initial letter for the company badge.
     */
    public function companyInitial(): string
    {
        $trimmed = trim($this->company);

        return $trimmed !== '' ? Str::upper(Str::substr($trimmed, 0, 1)) : '?';
    }
}

