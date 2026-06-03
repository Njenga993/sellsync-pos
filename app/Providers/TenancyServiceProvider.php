<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenancyServiceProvider extends ServiceProvider
{
    public function events()
    {
        return [
            // Tenant events - database creation disabled intentionally
            // We use single-database multi-tenancy via tenant_id columns
            \Stancl\Tenancy\Events\CreatingTenant::class => [],
            \Stancl\Tenancy\Events\TenantCreated::class => [],
            \Stancl\Tenancy\Events\SavingTenant::class => [],
            \Stancl\Tenancy\Events\TenantSaved::class => [],
            \Stancl\Tenancy\Events\UpdatingTenant::class => [],
            \Stancl\Tenancy\Events\TenantUpdated::class => [],
            \Stancl\Tenancy\Events\DeletingTenant::class => [],
            \Stancl\Tenancy\Events\TenantDeleted::class => [],

            // Domain events
            \Stancl\Tenancy\Events\DomainCreated::class => [],
            \Stancl\Tenancy\Events\DomainDeleted::class => [],

            // Tenancy events
            \Stancl\Tenancy\Events\InitializingTenancy::class => [],
            \Stancl\Tenancy\Events\TenancyInitialized::class => [],
            \Stancl\Tenancy\Events\EndingTenancy::class => [],
            \Stancl\Tenancy\Events\TenancyEnded::class => [],
            \Stancl\Tenancy\Events\RevertingToCentralContext::class => [],
            \Stancl\Tenancy\Events\RevertedToCentralContext::class => [],
        ];
    }

    public function register()
    {
        //
    }

    public function boot()
    {
        $this->bootEvents();
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof \Illuminate\Events\QueuedClosure) {
                    \Illuminate\Support\Facades\Event::listen($event, $listener);
                } else {
                    \Illuminate\Support\Facades\Event::listen($event, $listener);
                }
            }
        }
    }
}