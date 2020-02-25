<?php

namespace App\Providers;

use App\Events\Auth\ChangePassword;
use App\Events\Auth\DocumentCreated;
use App\Events\Auth\DocumentDeleted;
use App\Events\Auth\DocumentUpdated;
use App\Events\Auth\SignIn;
use App\Events\Auth\UserUpdate;
use App\Listeners\Auth\DocumentDeletedWrite;
use App\Listeners\Auth\DocumentUpdatedWrite;
use App\Listeners\Auth\UserRegistered;
use App\Listeners\Auth\WriteChangePassword;
use App\Listeners\Auth\WriteDocumentCreate;
use App\Listeners\Auth\WriteSignIn;
use App\Listeners\Auth\WriteUserUpdate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            UserRegistered::class,
        ],
        SignIn::class => [
            WriteSignIn::class,
        ],
        ChangePassword::class => [
            WriteChangePassword::class,
        ],
        UserUpdate::class => [
            WriteUserUpdate::class,
        ],

        //Документы
        DocumentCreated::class => [
            WriteDocumentCreate::class,
        ],
        DocumentUpdated::class=>[
            DocumentUpdatedWrite::class,
        ],
        DocumentDeleted::class=>[
            DocumentDeletedWrite::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
