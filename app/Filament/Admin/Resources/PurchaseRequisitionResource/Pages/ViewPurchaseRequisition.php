<?php

namespace App\Filament\Admin\Resources\PurchaseRequisitionResource\Pages;

use App\Filament\Admin\Resources\PurchaseRequisitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseRequisition extends ViewRecord
{
    protected static string $resource = PurchaseRequisitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
