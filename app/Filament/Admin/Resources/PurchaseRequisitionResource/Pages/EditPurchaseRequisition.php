<?php

namespace App\Filament\Admin\Resources\PurchaseRequisitionResource\Pages;

use App\Filament\Admin\Resources\PurchaseRequisitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseRequisition extends EditRecord
{
    protected static string $resource = PurchaseRequisitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
