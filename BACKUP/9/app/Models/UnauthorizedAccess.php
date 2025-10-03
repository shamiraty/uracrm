<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UnauthorizedAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'route_name',
        'url_attempted',
        'method',
        'user_role',
        'required_roles',
        'ip_address',
        'user_agent',
        'user_details',
        'attempted_at'
    ];

    protected $casts = [
        'user_details' => 'array',
        'attempted_at' => 'datetime'
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log unauthorized access attempt
     */
    public static function logAttempt($user, $routeName, $url, $method, $requiredRoles = [])
    {
        return self::create([
            'user_id' => $user->id,
            'route_name' => $routeName,
            'url_attempted' => $url,
            'method' => $method,
            'user_role' => $user->roles->first()->name ?? 'none',
            'required_roles' => implode(',', $requiredRoles),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_details' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'branch' => $user->branch->name ?? null,
                'region' => $user->region->name ?? null,
                'department' => $user->department->name ?? null,
                'district' => $user->district->name ?? null,
            ],
            'attempted_at' => now()
        ]);
    }

    /**
     * Check for frequent unauthorized attempts by user
     */
    public static function hasFrequentAttempts($userId, $minutes = 10, $threshold = 3)
    {
        return self::where('user_id', $userId)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes($minutes))
            ->count() >= $threshold;
    }

    /**
     * Get recent attempts for a user
     */
    public static function getRecentAttemptsForUser($userId, $hours = 24)
    {
        return self::where('user_id', $userId)
            ->where('attempted_at', '>=', Carbon::now()->subHours($hours))
            ->orderBy('attempted_at', 'desc')
            ->get();
    }
}
