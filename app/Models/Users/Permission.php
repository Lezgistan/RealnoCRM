<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;

/**
 * App\Models\Users\Permission
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission whereName($value)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Permission filter($frd)
 */
class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait;
    use Notifiable;

    protected $table = 'permissions';
    protected $primaryKey = 'id';
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    /**
     * @param string|null $display_name
     */
    public function setDisplayName(?string $display_name): void
    {
        $this->display_name = $display_name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
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
                            return $query->orWhere('name', 'like', '%' . $value . '%')
                                ->orWhere('display_name', 'like', '%' . $value . '%')
                                ->orWhere('description', 'like', '%' . $value . '%');
                        });
                    }
                    break;
            }
        }
        return $query;
    }
}
