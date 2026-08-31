<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to quickly log an action.
     * 
     * @param string $action e.g. 'created', 'updated', 'deleted', 'login'
     * @param string|null $description
     * @param string $status e.g. 'success', 'failed'
     */
    public static function logAction($action, $description = null, $status = 'success')
    {
        return self::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'action' => $action,
            'description' => $description,
            'status' => $status,
        ]);
    }
}
