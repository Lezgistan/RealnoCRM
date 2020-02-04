<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;
use Laratrust\Traits\LaratrustTeamTrait;

/**
 * App\Models\Users\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Role filter($frd)
 */
class Role extends LaratrustRole
{
    use LaratrustRoleTrait;
    use Notifiable;

    protected $table = 'roles';

    protected $primaryKey = 'id';
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
