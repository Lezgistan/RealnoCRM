<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Users\UserLog
 *
 * @property int $id
 * @property int $event_id
 * @property int|null $ip
 * @property int|null $user_id
 * @property int $targetable_id
 * @property string $targetable_type
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereTargetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereTargetableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Users\UserLog $targetable
 * @property-read \App\Models\Users\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserLog filterUser($userId)
 */
class UserLog extends Model
{
    protected $table = 'user_logs';
    protected $fillable = [
        'user_id',
        'event_id',
        'ip',
        'targetable_id',
        'targetable_type',
        'payload',
    ];
    protected $events = [
        1 => 'Создал пользователя',
        2 => 'Изменил данные пользователя',
        3 => 'Установил новый пароль пользователю',
        4 => 'Вошел в систему',
        5 => 'Изменил пароль пользователя',
        6 => 'Загрузил документ',
        7 => 'Обновил документ',
        8 => 'Удалил документ',
    ];
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * @return MorphTo
     */
    public function targetable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param int|null $userId
     * @return string
     */
    public function getName(int $userId = null): string
    {
        $author = null;

        if ($userId !== $this->getUserId()) {
            $author = $this->getUser()->getName();
        }

        $targetable = null;
        if (null !== $this->getTargetable() && $this->getTargetable()->getKey() !== $userId) {
            $targetable = ' <a href="' . $this->getTargetable()->getUrl() . '">' . $this->getTargetable()->getName() . '</a>';
        } elseif ((null === $this->getTargetable() && $this->event_id === 8) | (null === $this->getTargetable() && $this->event_id === 7) | (null === $this->getTargetable() && $this->event_id === 6)
        ) {
            $targetable = " <span class='text-danger'>ДОКУМЕНТ НЕ НАЙДЕН</span>";
        } elseif (null === $this->getTargetable()) {
            $targetable = " <span class='text-danger'>ПОЛЬЗОВАТЕЛЬ НЕ НАЙДЕН</span>";
        }

        return $author . ' ' . $this->events[$this->getEventId()] . $targetable;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


    /**
     * Мутатор – изменяем данные перед записью в атрибут
     * @param array $value
     */
    public function setPayloadAttribute(array $value): void
    {
        $this->attributes['payload'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->event_id;
    }

    /**
     * @param int $event_id
     */
    public function setEventId(int $event_id): void
    {
        $this->event_id = $event_id;
    }

    /**
     * @return int|null
     */
    public function getIp(): ?string
    {
        return long2ip($this->ip);
    }

    /**
     * @param int|null $ip
     */
    public function setIp(?int $ip): void
    {
        $this->ip = ip2long($ip);
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string|null
     */
    public function getPayload(): ?array
    {
        return $this->payload;
    }

    /**
     * @param string|null $payload
     */
    public function setPayload(?string $payload): void
    {
        $this->payload = $payload;
    }


    /**
     * @return Model|null
     */
    public function getTargetable(): ?Model
    {
        return $this->targetable;
    }

    /**
     * @param UserLog $targetable
     */
    public function setTargetable(UserLog $targetable): void
    {
        $this->targetable = $targetable;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function unsetUnnecessaryFields(array $array): array
    {
        $fields = [
            'id',
            'updated_at',
            'created_at',
            'deleted_at',
            'email_verified_at',
        ];

        foreach ($fields as $field) {
            if (isset($array[$field])) {
                unset($array[$field]);
            }
        }
        return $array;
    }

    /**
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeFilterUser(Builder $query, int $userId): Builder
    {
        return $query->where(function (Builder $query) use ($userId): Builder {
            return $query->orWhere('user_id', $userId)
                ->orWhere(function (Builder $query) use ($userId): Builder {
                    return $query->where('targetable_id', $userId)
                        ->where('targetable_type', User::class);
                });;
        });
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
                            return $query->orWhere('id', 'like', '%' . $value . '%')
                                ->orWhere('event_id', 'like', '%' . $value . '%');

                        });
                    }
                    break;
            }
        }
        return $query;
    }

    /**
     * @param int $eventId
     * @param array $payload
     * @param Model $model
     * @return UserLog
     */
    public static function add(int $eventId, array $payload, Model $model): self
    {
        return self::create([
            'user_id' => auth()->id() ?? 1,
            'ip' => ip2long(request()->ip()),
            'event_id' => $eventId,
            'targetable_id' => $model->getKey(),
            'targetable_type' => get_class($model),
            'payload' => self::unsetUnnecessaryFields($model->toArray()),
        ]);
    }
}
