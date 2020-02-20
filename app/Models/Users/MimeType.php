<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class MimeType extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'extension',
    ];

    public $timestamps = false;

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->{'extension'};
    }

    /**
     * @param string $extension
     * @return MimeType
     */
    public function setExtension(string $extension): MimeType
    {
        $this->{'extension'} = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->{'name'};
    }

    /**
     * @param string $name
     * @return MimeType
     */
    public function setName(string $name): MimeType
    {
        $this->{'name'} = $name;
        return $this;
    }


}
