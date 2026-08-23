<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',
        'portal_role',
    ];

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'editor'], true);
    }

    /**
     * Whether this user may enter the operations portal at all.
     */
    public function hasPortalAccess(): bool
    {
        return $this->portalRole() !== null;
    }

    /**
     * The portal capability level, or null when portal access was never granted.
     */
    public function portalRole(): ?PortalRole
    {
        return PortalRole::tryFrom((string) $this->portal_role);
    }

    /**
     * The portal identity behind this login — who tasks get assigned to and who
     * daily work updates belong to.
     *
     * @return HasOne<Employee, $this>
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * The employee record for this user, created on first portal use.
     */
    public function portalEmployee(): Employee
    {
        $employee = $this->employee ?? Employee::forUser($this);

        // Refresh the cached relation: a lazy-loaded null would otherwise stick
        // around for the rest of the request even after the record was created.
        $this->setRelation('employee', $employee);

        return $employee;
    }

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
}
