<?php

namespace App\Models\Users;

use App\Models\Users\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * App\Models\Users\File
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $fileable_id
 * @property string|null $fileable_type
 * @property string $local_path
 * @property int|null $disk_id
 * @property string $name
 * @property int $size
 * @property array $payload
 * @property int|null $client_mime_type_id
 * @property int|null $storage_mime_type_id
 * @property string $hash
 * @property \Illuminate\Support\Carbon|null $file_delete_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Users\MimeType|null $clientMimeType
 * @property-read \App\Models\Users\File|null $fileable
 * @property-read \App\Models\Users\MimeType $mimeType
 * @property-read \App\Models\Users\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File filterDocumentVersions($fileableId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereClientMimeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereDiskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereFileDeleteAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereLocalPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereStorageMimeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File whereUserId($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    protected $table = 'files';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'fileable_id',
        'fileable_type',
        'local_path',
        'disk',
        'name',
        'size',
        'payload',
        'file_delete_at',
        'client_mime_type_id',
        'storage_mime_type_id',
        'hash',
    ];

    protected $dates = [
        'file_delete_at',
        'updated_at',
        'created_at',
    ];


    /**
     * @return BelongsTo
     */
    public function mimeType(): BelongsTo
    {
        return $this->belongsTo(MimeType::class, 'mime_type_id');
    }

    /**
     * @return bool
     */
    public function isExists(): bool
    {
        return $this->getStorage()->exists($this->getLocalPath());
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getCreatedAt(): ?\Illuminate\Support\Carbon
    {
        return $this->created_at;
    }



    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getUpdatedAt(): ?\Illuminate\Support\Carbon
    {
        return $this->updated_at;
    }



    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->{'hash'};
    }

    public function setHash(string $source): void
    {
        $this->{'hash'} = hash('sha3-512', $source);
    }


    /**
     * @return BelongsTo
     */
    public function clientMimeType(): BelongsTo
    {
        return $this->belongsTo(MimeType::class, 'client_mime_type_id');
    }

    /**
     * @return MimeType
     */
    public function getMimeType(): ?MimeType
    {
        return $this->mimeType;
    }

    /**
     * @return MimeType
     */
    public function getClientMimeType(): ?MimeType
    {
        $mimeType = $this->clientMimeType;
        return $mimeType;
    }


    /**
     * @var string
     */
    public $disk = 's3';

    /**
     * @return int|null
     */
    public function getMimeTypeId(): ?int
    {
        return $this->mime_type_id;
    }

    /**
     * @param int|null $mime_type_id
     */
    public function setMimeTypeId(?int $mime_type_id): void
    {
        $this->mime_type_id = $mime_type_id;
    }

    /**
     * @return int|null
     */
    public function getClientMimeTypeId(): ?int
    {
        return $this->client_mime_type_id;
    }

    /**
     * @param int|null $client_mime_type_id
     */
    public function setClientMimeTypeId(?int $clientMimeTypeId): void
    {
        $this->{'client_mime_type_id'} = $clientMimeTypeId;
    }

    /**
     * @param string $publicPath
     */
    public function setPublicPath(string $publicPath): void
    {
        $this->{'public_path'} = $publicPath;
    }

    /**
     * @param string $localPath
     */
    public function setLocalPath(string $localPath): void
    {
        $this->{'local_path'} = $localPath;
    }


    /**
     * @var array
     */
    protected $casts = [
        'payload' => 'array'
    ];

    /**
     * @param array $payload
     */
    public function setPayload(array $payload): void
    {
        $this->{'payload'} = $payload;
    }

    /**
     * @param string $path
     * @param string $source
     * @return bool
     */
    public function put(string $path, string $source): bool
    {
        $result = false;
        try {
            $result = $this->getStorage()->put($path, $source);
        } catch (\Exception $exception) {
            Log::critical('File@put ' . $exception->getMessage(), (array)$exception);
        }
        return $result;
    }


    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->{'size'} = $size;
    }

    /**
     * @param string $mime
     */
    public function setMime(string $mime): void
    {
        $this->{'mime'} = $mime;
    }


    /**
     * @var FilesystemAdapter
     */
    private $storage;

    /**
     * @return string
     */
    public function getSizeFormatted(): string
    {
        $size = $this->getSize();
        if (null !== $size) {
            $size = round($size / 1024 / 1024, 2) . ' Мб';
        } else {
            $size = 'Размер не определен, возможно файл загружен с ошибкой';
        }

        return $size;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->{'size'};
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fileable(): ?MorphTo
    {
        return $this->morphTo('fileable', 'fileable_type', 'fileable_id');
    }


    /**
     * @param UploadedFile $uploadedFile
     * @return \App\Models\Files\File
     */
    public function setMimeByUploadFile(UploadedFile $uploadedFile): self
    {
        $clientMimeType = MimeType::where('name', $uploadedFile->getClientMimeType())->first();
        if (null !== $clientMimeType) {
            $this->setClientMimeTypeId($clientMimeType->getKey());
        } else {
            Log::info($uploadedFile->getClientMimeType() . ' client mime type not found');
        }
        $mimeType = MimeType::where('name', $uploadedFile->getMimeType())->first();
        if (null !== $mimeType) {
            $this->setMimeTypeId($mimeType->getKey());
            $this->setMime($uploadedFile->getClientMimeType());
            Log::info('mime type "' . $uploadedFile->getMimeType() . '" on file "' . $uploadedFile->getClientOriginalName() . '" not found');
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function setMimeByStorage(): self
    {
        $mime = $this->getStorage()->mimeType($this->getLocalPath());
        $this->setMime($mime);
        $mimeType = MimeType::where('name', $mime)->first();
        if ($mimeType) {
            $this->setClientMimeTypeId($mimeType->getKey());
            $this->setMimeTypeId($mimeType->getKey());
        }
        return $this;
    }

    public function delete()
    {
        $this->eraseFromStorage();
        return parent::delete(); // TODO: Change the autogenerated stub
    }


    /**
     * @param $file
     * @param string|null $prefix
     * @param Carbon|null $fileDeleteAt
     * @return File|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download($file, string $prefix = null, Carbon $fileDeleteAt = null)
    {
        try {

            /**
             * @var FilesystemAdapter
             */
            $storage = $this->getStorage();
            /**
             * Добавить проверку на наличие файла с таким именем
             */
            $path = $this->getInternalPath($file, $prefix);
            $this->{'local_path'} = $path;
            $source = $this->getFileSource($file);


            $this->getStorage()->put($path, $source);
            $this->{'size'} = $storage->getSize($path);
            $this->{'public_path'} = $storage->url($path);
            $this->{'file_delete_at'} = $fileDeleteAt ?? Carbon::now()->addDays(7);

            if (null === $this->getUserId() && auth()->check()) {
                $this->setUserId(auth()->id());
            }

            if (null === $this->getName()) {
                $this->setName($this->getFileName($file));
            }
            /**
             * @var UploadedFile $file
             */
            if ($file instanceof UploadedFile) {
                $this->setMimeByUploadFile($file);
            } else {
                $this->setMimeByStorage();
            }


            $this->save();
            $result = true;
        } catch (\Exception $exception) {
            if (isset($path) && $this->getStorage()->exists($path)) {
                $this->getStorage()->delete($path);
            }
            Log::critical('File@download ' . $this->getKey() . ' ' . $exception->getMessage());
            $result = false;
        }

        $file = true === $result ? $this : null;

        return $file;
    }

    /**
     * @param Carbon $date
     */
    public function setFileDelete(Carbon $date): void
    {
        $this->{'file_delete_at'} = $date;
    }

    /**
     * @return Carbon|null
     */
    public function getFileDeleteAt(): ?Carbon
    {
        return $this->{'file_delete_at'};
    }

    /**
     * @param UploadedFile $file
     * @param string $localPathWithoutExtension
     * @return File
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function addUploadFile(UploadedFile $file, string $localPathWithoutExtension): File
    {
        $source = $file->get();
        /**
         * Ищем mime type из upload file
         */
        $clientMimeTypeName = $file->getClientMimeType();

        /**
         * @var MimeType $clientMimeType
         */
        $clientMimeType = MimeType::where('name', $clientMimeTypeName)->first();
        if (null !== $clientMimeType) {
            $this->setClientMimeTypeId($clientMimeType->getKey());
        }


        $path = $localPathWithoutExtension . '.' . $clientMimeType->getExtension();
        $this->getStorage()->put($path, $source);
        $this->init($path);
        $this->setHash($source);
        $this->setLocalPath($path);
        $this->setName($file->getClientOriginalName());
        $this->save();
        return $this;
    }





    /**
     * @return FilesystemAdapter
     */
    public function getStorage(): FilesystemAdapter
    {
        $storage = $this->storage;
        if ($storage === null) {
            /**
             * @var FilesystemAdapter $storage
             */
            $storage = $this->getDisk();
            $this->storage = $storage;
        }
        return $storage;
    }


    /**
     * @param string $path
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function init(string $path): void
    {
        $storage = $this->getDisk();
        $mimeName = $storage->mimeType($path);
        $mimeType = MimeType::where('name', $mimeName)->first();
        if (null !== $mimeType) {
            $this->setStorageMimeTypeId($mimeType->getKey());
        }
        $this->setSize($storage->getSize($path));
        $this->setLocalPath($path);
        $this->setUserId(auth()->id() ?? 1);
    }

    /**
     * @param int $mimeTypeId
     */
    public function setStorageMimeTypeId(int $mimeTypeId): void
    {
        $this->{'storage_mime_type_id'} = $mimeTypeId;
    }

    /**
     * @param string $disk
     */
    public function setDisk(string $disk)
    {
        $this->{'disk'} = $disk;
    }

    /**
     * @param $file
     * @param string|null $prefix
     * @param string|null $folder
     * @param string|null $extension
     * @return string
     */
    public function getInternalPath($file, string $prefix = null, string $folder = null, string $extension = null): string
    {
        if (null !== $prefix) {
            $prefix = mb_strtolower($prefix, 'UTF-8');
            $prefix = trim($prefix);
            $prefix = Str::slug($prefix);
            $prefix .= '-';
        }

        $storage = $this->getStorage();

        $fullFileName = $this->getFileName($file);

        if (null === $extension) {
            $extension = '';
            /**
             * @var UploadedFile $file
             */
            if ($file instanceof UploadedFile) {
                $extension = trim($file->getClientOriginalExtension());
            } else {
                $extension = $this::getExtensionFromPathInfo($fullFileName);
            }
        }


        $name = $this->getName() ?? $this::getNameFromPathInfo($fullFileName);

        $fileName = Str::slug(
                mb_strimwidth(mb_strtolower($name, 'UTF-8')
                    , 0, 55, null, 'UTF-8')
            ) . '.' . $extension;

        $postfix = '';

        $i = null;

        do {
            $path = 'public/'
                . (null !== $folder ? $folder . '/' : null)
                . date('Y/m/d/')
                . $prefix . $postfix . $fileName;
            ++$i;
            $postfix = $i . '-';
        } while ($storage->exists($path));
        return $path;
    }

    /**
     * @param $file
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFileSource($file): ?string
    {
        $type = $this->getFileType($file);
        switch ($type) {
            case 'uploadedFile':
                {
                    /**
                     * @var UploadedFile $file
                     */
                    $source = $file->get();
                }
                break;
            case 'url':
                {
                    /**
                     * @var string $image
                     */
                    $source = $this->getHttpContents($file);
                }
                break;
            case 'realpath':
                {
                    $source = file_get_contents($file);
                }
                break;


            default:
                {
                    /**
                     * XML / JSON / BLOB
                     */
                    /**
                     * @var string $image
                     */
                    $source = $file;
                }
                break;
        }

        return $source;
    }

    /**
     * @param $image
     *
     * @return string
     */
    public function getFileType($file): string
    {
        $type = 'blob';

        if ($file instanceof UploadedFile || is_uploaded_file($file)) {
            $type = 'uploadedFile';
        } elseif (\is_string($file)) {
            if (false !== filter_var($file, FILTER_VALIDATE_URL)) {
                $type = 'url';
            } elseif (false !== stripos($file, 'base64')) {
                $type = 'base64';
            } elseif (null !== json_decode($file, true)) {
                $type = 'json';
            } elseif (is_string($file) && true === $this->isValidXml($file)) {
                $type = 'xml';
            }
        }
        return $type;
    }

    /**
     * @param string $content
     * @return bool
     */
    private function isValidXml(string $content): bool
    {
        $content = trim($content);
        if (empty($content)) {
            return false;
        }
        //html go to hell!
        if (stripos($content, '<!DOCTYPE html>') !== false) {
            return false;
        }

        libxml_use_internal_errors(true);
        simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return empty($errors);
    }

    /**
     * @return bool
     */
    public function isBase64($data): bool
    {
        $decoded_data = base64_decode($data, true);
        $encoded_data = base64_encode($decoded_data);
        if ($encoded_data !== $data) return false;
        else if (!ctype_print($decoded_data)) return false;

        return true;
    }

    /**
     * @param string $url
     *
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHttpContents(string $url): ?string
    {

        $return = null;

        $client = new Client([
            'timeout' => 10,
        ]);
        try {
            $response = $client->head($url);
            if ($response->getStatusCode() === 200) {
                $response = $client->request('GET', $url);
                $return = $response->getBody()->getContents();
            }
        } catch (RequestException $e) {
            Log::critical('file@getHttpContents failed', (array)$e);
        }

        return $return;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->{'user_id'};
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->{'user_id'} = $userId;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->{'name'};
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return '#' . $this->getKey() . ' ' . $this->{'fileable_id'} . ' ' . $this->{'fileable_type'};
    }

    /**
     * @param string|null $name
     */
    public function setName(string $name = null): void
    {
        $this->{'name'} = mb_strimwidth($name, 0, 255, '', 'UTF-8');
    }

    /**
     * @param $file
     * @return string
     */
    public function getFileName($file): string
    {
        $type = $this->getFileType($file);

        switch ($type) {
            case 'uploadedFile':
                {
                    /**
                     * @var UploadedFile $file
                     */
                    $source = $file->getClientOriginalName();
                }
                break;
            case 'url':
                {
                    $source = pathinfo($file)['basename'] ?? null;
                    /**
                     * @var string $image
                     */
                }
                break;

            case 'realpath':
                {
                    $source = pathinfo($file)['basename'] ?? null;
                }
                break;

            case 'base64':
                {
                    $source = uniqid(null, false) . '.base64';
                }
                break;

            case 'blob':
                {
                    $source = uniqid(null, false) . '.txt';
                }
                break;

            case 'xml':
                {
                    $source = uniqid(null, false) . '.xml';
                }
                break;

            case 'json':
                {
                    $source = uniqid(null, false) . '.json';
                }
                break;

            default:
                {
                    Log::warning('File@getFileName failed file type not detected ' . $type);
                }
                break;
        }
        return $source;
    }

    /**
     * @return string
     */
    public function getNameForDowloadFile(): string
    {
        return $this->created_at->format('d-m-Y_h-i_') . Str::slug($this->getNameWithoutExtenstion()) . '.' . $this->getExtension();
    }


    /**
     * @return string
     */
    public function getNameWithoutExtenstion(): string
    {
        return pathinfo($this->getName())['filename'];
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getNameFromPathInfo(string $name): ?string
    {
        return pathinfo($name)['filename'] ?? null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getExtensionFromPathInfo(string $name): ?string
    {

        $extension = pathinfo($name)['extension'] ?? 'undefined';
        if (false !== strpos($extension, '?')) {
            $extensionArray = explode('?', $extension);
            $extension = $extensionArray[0];
        }
        $extension = trim($extension);
        $extension = mb_strtolower($extension, 'UTF-8');
        return $extension;
    }


    /**
     * @param Builder $query
     * @param array $frd
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $frd): Builder
    {
        foreach ($frd as $key => $value) {
            if (null === $value) {
                continue;
            }

            switch ($key) {
                case 'search':{
                    $query->where('name','like','%'.$value.'%');
                }break;
                default:
                    {
                        if (true === \in_array($key, $this->getFillable(), true)) {
                            $query->where($key, $value);
                        }
                    }
                    break;
            }
        }

        return $query;
    }


    /**
     * @return bool
     */
    public function eraseFromStorage(): bool
    {
        return $this->getStorage()->delete($this->getLocalPath());
    }

    /**
     * @return null|string
     */
    public function getLocalPath(): ?string
    {
        return $this->{'local_path'};
    }

    /**
     * @return bool
     */
    public function isDeletable(): bool
    {
        $fileDeleteAt = $this->getFileDeleteAt();
        return (null !== $fileDeleteAt && $fileDeleteAt < Carbon::now());
    }

    /**
     * @param bool $check
     * @param bool $debug
     */
    public static function clear(bool $check = false, bool $debug = false): void
    {
        self::orderBy('id')->chunk(500, static function (Collection $files) use ($check, $debug) {
            /**
             * @var File $file
             */
            foreach ($files as $file) {
                if ($debug) {
                    echo $file->getFullName();
                }

                if (true === $check) {
                    /**
                     * С проверкой существования файла
                     */
                    $requirement = true === $file->isDeletable() || !$file->isExists() === true;
                } else {
                    /**
                     * Без проверки существования файла
                     */
                    $requirement = true === $file->isDeletable();
                }

                if ($requirement) {
                    if ($debug) {
                        echo ' deleted ';
                    }
                    $file->eraseFromStorage();
                    $file->delete();
                } else if ($debug) {
                    echo '+';
                }
                if ($debug) {
                    echo "\n";
                }
            }
        });
    }

    /**
     * @return string
     */
    public function getPublicPath(): string
    {
        return $this->getDisk()->url($this->getLocalPath());
    }

    /**
     * @return FilesystemAdapter
     */
    public function getDisk(): FilesystemAdapter
    {
        return Storage::disk('public');
    }

    /***
     * @return array
     */
    public function getPayload(): array
    {
        return $this->{'payload'};
    }

}
