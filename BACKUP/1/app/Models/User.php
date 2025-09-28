<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'branch_id',
        'role_id',
        'designation',
        'rank',
        'status',
        'phone_number',
        'region_id',     // New field
        'department_id', // New field
        'district_id',   // New field
        'command_id',    // New field
        'first_login',
        'last_password_change',
        'last_login',
        'last_activity', // New field for tracking online status
        'login_attempts',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        //'first_login' => 'boolean',
        'last_password_change' => 'datetime',
        'last_login' => 'datetime',
        'last_activity' => 'datetime', // New field for online status
    ];

    protected $attributes = [
        'first_login' => 0, // Set default value to 0
    ];

    // If you are using Spatie's role system, you won't need this.
    // If you are using a custom roles system:
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function enquiries()
    {
        return $this->belongsToMany(Enquiry::class, 'enquiry_user');
    }

    //added new relationships
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id'); // New relationship
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id'); // New relationship
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id'); // New relationship
    }

    public function command()
    {
        return $this->belongsTo(Command::class, 'command_id'); // New relationship
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class); // Assuming the 'User' has a 'rank_id' field
    }

    public function getRankNameAttribute()
    {
        return $this->rank->name ?? 'N/A';
    }

    /**
     * Check if user is currently online
     * 
     * @param int $minutes
     * @return bool
     */
    public function isOnline($minutes = 5)
    {
        return $this->last_activity && $this->last_activity->greaterThan(now()->subMinutes($minutes));
    }

    /**
     * Update user's last activity timestamp
     */
    public function updateActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Scope to get online users
     */
    public function scopeOnline($query, $minutes = 5)
    {
        return $query->where('last_activity', '>', now()->subMinutes($minutes));
    }

    /**
     * Get the user's full display name with rank
     */
    public function getDisplayNameAttribute()
    {
        $rank = $this->rank ? $this->rank->name . ' ' : '';
        return $rank . $this->name;
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }
}