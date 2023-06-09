<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'is_admin',
        'company_id',
        'email_verified_at',
        'image',
        'linkedin',
        'bio',
        'phone'
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
    ];


    /**
     * Takes string password and Hashes it
     *
     */
    public function password() : Attribute
    {
        return Attribute::set(fn ($value) => Hash::make($value));
    }

    /**
     * get user firstname
     *
     */
    public function getFirstnameAttribute(): string
    {
        return (explode(" ",$this->name))[0];
    }

    /**
     * get user photo
     *
     */
    public function getPhotoAttribute(): string
    {
        $defaultLogoPath = url('/images/avatar.png');
        $logoFolder = 'public/user-images';

        if ($this->image && Storage::exists($logoFolder . '/' . $this->image)) {
            return url(Storage::url($logoFolder . '/' . $this->image));
        } else {
            return $defaultLogoPath;
        }
    }

    /**
     * get user initials
     *
     */
    public function getInitialsAttribute(): string
    {
        $nameArray = explode(' ',$this->name);
        $initials = "";

        if ($nameArray[0] !=='' && $nameArray[1] !== ''){
            $initials = substr($nameArray[0], 0, 1) . substr($nameArray[1], 0, 1);
            return strtoupper($initials);
        }
        return $initials;
    }

    public function scopeEnabled($query){
        return $query->where('enabled',true);
    }


    /**
     * get user company
     *
     */
    public function company(){
        return $this->belongsTo(Company::class);
    }

}
