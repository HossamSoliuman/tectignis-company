<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Portal\PortalRole;
use App\Enums\UserRole;
use App\Models\Portal\Employee;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
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
        'can_export_leads',
        'portal_role',
    ];

    /**
     * The website admin access level, or null for an unrecognised value.
     */
    public function userRole(): ?UserRole
    {
        return UserRole::tryFrom((string) $this->role);
    }

    /**
     * Whether this user may manage website content (the CMS).
     */
    public function isAdmin(): bool
    {
        return $this->userRole()?->hasCmsAccess() ?? false;
    }

    public function isSuperAdmin(): bool
    {
        return $this->userRole()?->isSuperAdmin() ?? false;
    }

    public function canViewLeads(): bool
    {
        return $this->userRole()?->canViewLeads() ?? false;
    }

    /**
     * Export is granted per user on top of view access; Super Admins always have it.
     */
    public function canExportLeads(): bool
    {
        return $this->isSuperAdmin() || ($this->canViewLeads() && $this->can_export_leads);
    }

    /**
     * Users a lead can be assigned to: everyone allowed to work leads.
     *
     * @return Builder<User>
     */
    public static function leadAssignees(): Builder
    {
        $roles = collect(UserRole::cases())
            ->filter(fn (UserRole $role): bool => $role->canManageLeads())
            ->map(fn (UserRole $role): string => $role->value)
            ->values()
            ->all();

        return static::query()->whereIn('role', $roles)->orderBy('name');
    }

    /**
     * Whether this user may sign in to the admin area through either door.
     */
    public function hasAdminAreaAccess(): bool
    {
        return $this->isAdmin() || $this->canViewLeads() || $this->hasPortalAccess();
    }

    /**
     * Where to land after signing in, based on what this user can reach.
     */
    public function adminHomeUrl(): string
    {
        return match (true) {
            $this->isAdmin() => route('admin.dashboard'),
            $this->canViewLeads() => route('admin.leads.index'),
            default => route('admin.portal.dashboard'),
        };
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
            'can_export_leads' => 'boolean',
        ];
    }
}
