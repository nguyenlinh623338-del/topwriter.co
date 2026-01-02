<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credits',
        'trial_used'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mối quan hệ với TrialRegistration
     */
    public function trialRegistrations()
    {
        return $this->hasMany(TrialRegistration::class);
    }

    public function canWriteArticle()
    {
        if (!$this->trial_used) {
            return true;
        }
        return $this->credits > 0;
    }

    public function useCredit()
    {
        if (!$this->trial_used) {
            $this->trial_used = true;
            $this->save();
            return true;
        }
        
        if ($this->credits > 0) {
            $this->credits--;
            $this->save();
            return true;
        }
        
        return false;
    }

    public function addCredits($amount)
    {
        $this->credits += $amount;
        $this->save();
    }
}
