<?php

namespace App\Observers;

use App\Models\LicenseRequest;

class LicenseRequestObserver
{
    public function creating(LicenseRequest $licenseRequest): void
    {
        // Calcular duración automáticamente antes de crear el registro
        $licenseRequest->duration_days = $licenseRequest->start_date->diffInDays($licenseRequest->end_date);
    }

    public function updating(LicenseRequest $licenseRequest): void
    {
        // Actualizar duración si las fechas cambian
        $licenseRequest->duration_days = $licenseRequest->start_date->diffInDays($licenseRequest->end_date) + 1;
    }

    /**
     * Handle the LicenseStoreRequest "created" event.
     */
    public function created(LicenseRequest $licenseRequest): void
    {
        //
    }

    /**
     * Handle the LicenseStoreRequest "updated" event.
     */
    public function updated(LicenseRequest $licenseRequest): void
    {
        //
    }

    /**
     * Handle the LicenseStoreRequest "deleted" event.
     */
    public function deleted(LicenseRequest $licenseRequest): void
    {
        //
    }

    /**
     * Handle the LicenseStoreRequest "restored" event.
     */
    public function restored(LicenseRequest $licenseRequest): void
    {
        //
    }

    /**
     * Handle the LicenseStoreRequest "force deleted" event.
     */
    public function forceDeleted(LicenseRequest $licenseRequest): void
    {
        //
    }
}
