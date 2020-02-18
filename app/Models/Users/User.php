<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Users\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User orWherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User orWhereRoleIs($role = '', $team = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePermissionIs($permission = '', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereRoleIs($role = '', $team = null, $boolean = 'and')
 * @property string $f_name
 * @property string $l_name
 * @property string $m_name
 * @property string $phone
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereFName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereLName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereMName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User wherePhone($value)
 * @property int $age
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User filter($frd)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\UserLog[] $logs
 * @property-read int|null $logs_count
 * @property string $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\UserLog[] $logsForMe
 * @property-read int|null $logs_for_me_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereImageUrl($value)
 * @property \Illuminate\Support\Carbon|null $last_active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\User whereLastActive($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\UserDoc[] $docs
 * @property-read int|null $docs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\DocumentVersion[] $documentversion
 * @property-read int|null $documentversion_count
 */
class User extends Authenticatable

{
    use LaratrustUserTrait;
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'f_name',
        'l_name',
        'm_name',
        'phone',
        'password',
        'age',
        'image_url',
        'last_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'age',
        'last_active',
    ];

    /**
     * @return string
     */
    public function getName(): string
    {
        return trim($this->getFirstName() . ' ' . $this->getMiddleName());
    }

    /**
     * @param string $name
     * @param int $typeId
     */
    public function setName(string $name, int $typeId = 0): void
    {
        switch ($typeId) {
            case 1:
                {
                    $type = 'f_name';
                }
                break;
            case 2:
                {
                    $type = 'm_name';
                }
                break;
            default:
                {
                    $type = 'l_name';
                }
                break;
        }
        $this->{$type} = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getEmailVerifiedAt(): ?string
    {
        return $this->email_verified_at;
    }

    /**
     * @param string|null $email_verified_at
     */
    public function setEmailVerifiedAt(?string $email_verified_at): void
    {
        $this->email_verified_at = $email_verified_at;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $hash = Hash::make($password);
        $this->{'password'} = $hash;
    }


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->f_name;
    }

    /**
     * @param string $f_name
     */
    public function setFirstName(string $f_name): void
    {
        $this->setName($f_name, 1);
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->l_name;
    }

    /**
     * @param string $l_name
     */
    public function setLastName(string $l_name): void
    {
        $this->setName($l_name, 0);
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->m_name;
    }

    /**
     * @param string $m_name
     */
    public function setMiddleName(string $m_name): void
    {
        $this->setName($m_name, 2);
    }

    /**
     * @return int
     */
    public function getAge(): int
    {

        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @param Builder $query
     * @param array $frd
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $frd): Builder
    {
        array_filter($frd);
        foreach ($frd as $key => $value) {
            if (null === $value) {
                continue;
            }
            switch ($key) {
                case 'search':
                    {
                        $query->where(function (Builder $query) use ($value): Builder {
                            return $query->orWhere('f_name', 'like', '%' . $value . '%')
                                ->orWhere('l_name', 'like', '%' . $value . '%')
                                ->orWhere('m_name', 'like', '%' . $value . '%')
                                ->orWhere('email', 'like', '%' . $value . '%');
                        });
                    }
                    break;
            }
        }
        return $query;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return route('users.show', $this);
    }

    /**
     * @return HasMany
     */
    public function documentVersions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    public function logsForMe()
    {
        return $this->morphMany(UserLog::class, 'targetable');
    }

    /**
     * @return HasMany
     */
    public function docs(): HasMany
    {
        return $this->hasMany(UserDoc::class);
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->image_url;
    }

    /**
     * @return string|null
     * Получаем публичный путь изображения
     * Передаем в переменную $imageUrl строку, содержащую путь до файла на сайте
     *
     */
    public function getImageUrlLocalPath(): ?string
    {
        $imageUrl = $this->getImageUrl();
        /**
         * Если путь существует, то заменяем http://realno.ebsp.ru/storage/ на ' '
         * Получаем из публичного пути локальный
         */
        if (null !== $imageUrl) {
            $imageUrl = str_replace('http://realno.ebsp.ru/storage/', '', $imageUrl);
        }
        return $imageUrl;
    }

    /**
     * @param string $image_url
     */
    public function setImageUrl(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getLastActive(): ?\Illuminate\Support\Carbon
    {
        return $this->last_active;
    }

    /**
     * @param \Illuminate\Support\Carbon|null $last_active
     */
    public function setLastActive(?\Illuminate\Support\Carbon $last_active): void
    {
        $this->last_active = $last_active;
    }


    public function isOnline()
    {
        return ($this->last_active > new \DateTime('-5 minutes') && \Auth::check()) ? true : false;
    }
}
