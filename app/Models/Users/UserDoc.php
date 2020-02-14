<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Users\UserDoc
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $filename
 * @property string $doc_url
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereDocUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\UserDoc whereUserId($value)
 */
class UserDoc extends Model
{
    protected $table = 'user_docs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'filename',
        'doc_url',
        'user_id',
    ];

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
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getDocUrl(): string
    {
        return $this->doc_url;
    }

    /**
     * @param string $doc_url
     */
    public function setDocUrl(string $doc_url): void
    {
        $this->doc_url = $doc_url;
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
