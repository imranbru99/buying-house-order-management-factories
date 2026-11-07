<?php

namespace App\Models;

use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use BelongsToTenant, HasFactory;

    protected $table = 'notification_preferences';

    protected $fillable = ['tenant_id', 'user_id', 'channel', 'event_key', 'is_enabled'];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

}
