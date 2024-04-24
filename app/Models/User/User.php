<?php

namespace App\Models\User;

use App\Traits\EloquentGetTableNameTrait;
use Carbon\Carbon;
use DomainException;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Random\RandomException;
use Throwable;

/**
 * App\Models\User\User
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $password
 * @property string $email
 * @property string|null $email_verified_at
 * @property int|null $role_id
 * @property string|null $phone
 * @property int $phone_auth
 * @property bool $phone_verified
 * @property string|null $phone_verify_token
 * @property \Illuminate\Support\Carbon|null $phone_verify_token_expire
 * @property string|null $remember_token
 * @property string|null $status
 * @property string|null $verify_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Role|null $rRole
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User wherePhoneAuth($value)
 * @method static Builder|User wherePhoneVerified($value)
 * @method static Builder|User wherePhoneVerifyToken($value)
 * @method static Builder|User wherePhoneVerifyTokenExpire($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereVerifyToken($value)
 * @method static count()
 * @method static create(array $array)
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;
    use EloquentGetTableNameTrait;

    protected $table = 'users';
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'password',
        'verify_token',
        'status',
        'email_verified_at',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'phone_verify'
    ];

    protected $casts = [
        'phone_verified'            => 'boolean',
        'phone_verify_token_expire' => 'datetime'
    ];

    public static function rolesList(): array
    {
        return [
            self::ROLE_USER      => 'User',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_ADMIN     => 'Admin',
        ];
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return self
     */
    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name'         => $name,
            'email'        => $email,
            'password'     => bcrypt($password),
            'verify_token' => Str::uuid(),
            'role'         => self::ROLE_USER,
            'status'       => self::STATUS_WAIT
        ]);
    }

    /**
     * @param $name
     * @param $email
     * @return self
     */
    public static function new($name, $email): self
    {
        return static::create([
            'name'     => $name,
            'email'    => $email,
            'password' => bcrypt(Str::random()),
            'role'     => self::ROLE_USER,
            'status'   => self::STATUS_ACTIVE
        ]);
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
     // return $this->role === self::ROLE_ADMIN;
        return $this->rRole->name === 'Admin';
    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * @return void
     */
    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already verified');
        }

        $this->update([
            'status'            => self::STATUS_ACTIVE,
            'verify_token'      => null,
            'email_verified_at' => now()
        ]);
    }

    /**
     * @param $role
     * @return void
     */
    public function changeRole($role): void
    {
        if (!in_array($role, [self::ROLE_USER, self::ROLE_ADMIN, self::ROLE_MODERATOR], true)) {
            throw new InvalidArgumentException('Undefined role "' . $role . '"');
        }

        if ($this->role === $role) {
            throw new DomainException('Role is already assigned');
        }

        $this->update(['role' => $role]);
    }

    /**
     * @return void
     */
    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        // $this->phone_auth = false;
        try {
            $this->saveOrFail();
        } catch (Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param Carbon $now
     * @return string
     * @throws Throwable
     * @throws RandomException
     */
    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new DomainException('Phone number is empty.');
        }
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($now)) {
            throw new DomainException('Token is already requested.');
        }
        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    /**
     * @param $token
     * @param Carbon $now
     * @return void
     * @throws Throwable
     */
    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new DomainException('Token is expired.');
        }
        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function enablePhoneAuth(): void
    {
        if (!empty($this->phone) && !$this->isPhoneVerified()) {
            throw new DomainException('Phone number is empty.');
        }
        $this->phone_auth = true;
        $this->saveOrFail();
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function disablePhoneAuth(): void
    {
        $this->phone_auth = false;
        $this->saveOrFail();
    }

    /**
     * @return bool
     */
    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }

    /**
     * @return bool
     */
    public function isPhoneAuthEnabled(): bool
    {
        return (bool)$this->phone_auth;
    }

    /**
     * @return bool
     */
    public function hasFilledProfile(): bool
    {
        return !empty($this->name) && !empty($this->last_name) && $this->isPhoneVerified();
    }

    /**
     * @return BelongsTo
     */
    public function rRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
