<?php

namespace App\Models\Users;

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
     * @return BelongsTo
     */
    public function document(): belongsTo
    {
        return $this->belongsTo(UserDoc::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
