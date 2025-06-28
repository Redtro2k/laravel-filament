<?php

namespace App\Filament\Admin\Resources\PurchaseRequisitionResource\Pages;

use App\Filament\Admin\Resources\PurchaseRequisitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseRequisition extends EditRecord
{
    protected static string $resource = PurchaseRequisitionResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin']) ?? false;
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
