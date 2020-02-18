<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Users\DocumentVersion
 *
 * @property int $id
 * @property int $document_id
 * @property int $version
 * @property int|null $user_id
 * @property-read \App\Models\Users\UserDoc $document
 * @property-read \App\Models\Users\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\DocumentVersion whereVersion($value)
 * @mixin \Eloquent
 */
class DocumentVersion extends Model
{
    protected $table = 'doc_version';

    protected $primaryKey = 'id';

    protected $fillable = [
        'document_id',
        'user_id',
        'version',
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
     * @return int
     */
    public function getDocumentId(): int
    {
        return $this->document_id;
    }

    /**
     * @param int $document_id
     */
    public function setDocumentId(int $document_id): void
    {
        $this->document_id = $document_id;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
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
    public function document(): belongsTo
    {
        return $this->belongsTo(UserDoc::class,'id','document_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterDocumentVersion(Builder $query, int $documentId): Builder
    {
        return $query->where(function (Builder $query) use ($documentId): Builder {
            return $query->orWhere('document_id', $documentId);
        });
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
                            return $query->orWhere('id', 'like', '%' . $value . '%')
                                ->orWhere('document_id', 'like', '%' . $value . '%')
                                ->orWhere('version', 'like', '%' . $value . '%')
                            ->orWhere('user_id', 'like', '%' . $value . '%');
                        });
                    }
                    break;
            }
        }
        return $query;
    }


}
