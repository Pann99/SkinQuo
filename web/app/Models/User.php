<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     /** @use HasFactory<UserFactory> */
//     use HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'first_name',
//         'last_name',
//         'email',
//         'mobile_number',
//         'password',
//         'birth_date',
//         'gender',
//         'avatar',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }

//     /**
//      * Relationship: User has many Consultations
//      */
//     public function consultations()
//     {
//         return $this->hasMany(Consultation::class);
//     }
// }


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'sex_id',
        'role_id',
        'date_birth',
        'created_at'
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;

    /**
     * Disable remember token since Supabase doesn't have this column
     */
    public $rememberTokenName = null;

    /**
     * Cast attributes to native types
     */
    protected $casts = [
        'created_at' => 'datetime',
        'date_birth' => 'date',
    ];

    /**
     * Relationship: User has many Consultations
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: User belongs to Sex
     */
    public function sex()
    {
        return $this->belongsTo(Sex::class, 'sex_id', 'id');
    }

    /**
     * Relationship: User belongs to Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}