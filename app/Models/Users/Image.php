<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

/**
 * App\Models\Users\Image
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
 * @property-read \App\Models\Users\Image|null $fileable
 * @property-read \App\Models\Users\MimeType $mimeType
 * @property-read \App\Models\Users\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\File filterDocumentVersions($fileableId)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereClientMimeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereDiskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereFileDeleteAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereLocalPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereStorageMimeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Image whereUserId($value)
 * @mixin \Eloquent
 */
class Image extends File
{

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @return ImageManager
     */
    public function getImageManager(): ImageManager
    {
        $imageManager = $this->imageManager;

        if (null === $imageManager) {

            $imageManager = new ImageManager(['driver' => 'imagick']);
            $this->imageManager = $imageManager;

        }

        return $imageManager;
    }



    /**
     * @param \Intervention\Image\Image $image
     * @param int $width
     * @param int $height
     * @return \Intervention\Image\Image
     */
    public function reduceImage(\Intervention\Image\Image $image, int $width, int $height)
    {
        if ($image->width() > $width || $image->height() > $height) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        return $image;
    }



    /**
     * @param \Intervention\Image\Image $imageManager
     */
    public function initImage(\Intervention\Image\Image $imageManager): void
    {
        $payload = [
            'width' => $imageManager->width(),
            'height' => $imageManager->height(),
        ];
        $this->setPayload($payload);
    }

    /**
     * @param \Intervention\Image\Image $image
     * @param string $extension
     * @param int $quality
     * @return string
     */
    public function encodeImage(\Intervention\Image\Image $image, string $extension = 'jpg', int $quality = 100): string
    {
        return (string)$image->encode($extension, $quality);
    }


    /**
     * @param UploadedFile $file
     * @param string $localPathWithoutExtension
     * @param Carbon|null $fileDeleteAt
     * @param int $quality
     * @param int $maxWidth
     * @param int $maxHeight
     * @return File|null
     */
    public function addImageFromUploadFile(UploadedFile $file,
                                           string $localPathWithoutExtension,
                                           int $maxWidth = 1000,
                                           int $maxHeight = 1000,
                                           int $quality = 100,
                                           Carbon $fileDeleteAt = null
    ):?File
    {
        $result = null;
        try {
            /**
             * Содержимое изображения
             */
            $source = $file->get();

            /**
             * Ищем mime type из upload file
             */
            $clientMimeTypeName = $file->getClientMimeType();

            /**
             * @var MimeType $clientMimeType
             */
            $clientMimeType  = MimeType::where('name',$clientMimeTypeName)->first();
            if (null !== $clientMimeType){
                $this->setClientMimeTypeId($clientMimeType->getKey());
            }

            /**
             * Внутренний путь файлового хранилища
             *
             * @var string $path
             */
            $path = $localPathWithoutExtension.'.'.$clientMimeType->getExtension();

            /**
             * svg — просто сохраняем
             */
            if ('svg' === $clientMimeType->getExtension()){
                $image = $source;

                $xmlget = simplexml_load_string($source);
                $xmlattributes = $xmlget->attributes();
                $width = round((float) $xmlattributes->width);
                $height = round((float) $xmlattributes->height);
                $payload = [
                    'width' => $width,
                    'height' => $height,
                ];
                $this->setPayload($payload);

            }else{
                /**
                 * Обрезаем и сжимаем изображение, если оно не svg
                 *
                 * @var ImageManager $image
                 */
                $manager = $this->getImageManager();
                $image = $manager->make($source);
                $image = $this->reduceImage($image, $maxWidth, $maxHeight);
                $image->getCore()->stripImage();
                $this->initImage($image);
                $image->trim('transparent');
                $image = $this->encodeImage($image, $clientMimeType->getExtension(),$quality);
            }

            /**
             * Генерируем хеш изображения, чтобы не продить дубликатов
             */
            $this->setHash($image);

            /**
             * Записываем на файловое хранилище
             */
            $putResult = $this->put($path,$image);

            /**
             * Записываем параметры картинки (обязательно после записи в файловое хранилище)
             */

            if (true === $putResult) {
                /**
                 * Заливаший пользователь, если не задан
                 */
                if (null === $this->getUserId()
                    && auth()->check()) {
                    $this->setUserId(auth()->id());
                }


                /**
                 * Устанавливаем имя файла, если не задано
                 */
                if (null === $this->getName()) {
                    $this->setName($file->getClientOriginalName());
                }

                /**
                 * Выставляем дату удаления (ее джоба будет проверять и удалять старые пикчи)
                 */
                if (null !== $fileDeleteAt) {
                    $this->setFileDelete($fileDeleteAt);
                }


                $this->init($path);
                $this->save();

                $result = $this;
            }
        }
        catch
        (\Exception $exception) {
            Log::critical('Image@download ' . $exception->getMessage(), [
                'line'=>$exception->getLine(),
            ]);
        }

        return $result;
    }

}
