<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\Note;
use App\Repositories\GroupRepository;
use App\Repositories\GroupRepositoryInterface;
use App\Repositories\NoteRepository;
use App\Repositories\NoteRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NoteRepositoryInterface::class, function ($app) {
            return new NoteRepository(new Note);
        });

        $this->app->bind(GroupRepositoryInterface::class, function ($app) {
            return new GroupRepository(new Group);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
