<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use \Rackbeat\UIAvatars\HasAvatar;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',         // ✅ make sure this is here
        'status',
        'is_admin',     // ✅ and this too
        'is_staff',
        'is_parent',
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
        'password' => 'hashed',
    ];


    public function isStudent()
    {
        return is_null($this->role) && !$this->is_parent;
    }
    

    public function isParent()
    {
        return $this->is_parent;
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isLibrarian()
    {
        return $this->role === 'librarian';
    }

    public function isAccountant()
    {
        return $this->role === 'accountant';
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    // staff
    public function isStaff()
    {
        return $this->is_staff;
    }

    public function isActive()
    {
        return $this->status;
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }

    // staff relationship
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }
}
