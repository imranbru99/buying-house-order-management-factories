<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Style extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes, HasUniversalSlug;

    protected $table = 'styles';

    protected $fillable = ['tenant_id', 'product_category_id', 'style_number', 'buyer_id', 'code', 'buyer_style_ref', 'name', 'slug', 'description', 'season', 'status'];

    protected function casts(): array
    {
        return [];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }
}
