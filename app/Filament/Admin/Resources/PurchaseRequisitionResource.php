<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PurchaseRequisitionResource\Pages;
use App\Filament\Admin\Resources\PurchaseRequisitionResource\RelationManagers;
use App\Models\PurchaseRequisition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseRequisitionResource extends Resource
{
    protected static ?string $model = PurchaseRequisition::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Purchasing Officer";


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('Request Details')
                ->description('Basic details about the purchase requisition request.')
                ->schema([
                    Forms\Components\Select::make('requester_id')
                        ->label('Requested By')
                        ->relationship(
                            name: 'requester',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->when(
                                !auth()->user()->hasRole('super_admin'),
                                fn ($query) => $query->whereHas('roles', fn ($q) =>
                                    $q->whereIn('name', ['member'])
                                )
                            )
                        )
                        ->disabled(fn () => auth()->user()->hasRole('member'))
                        ->default(fn () => auth()->user()->hasRole('member') ? auth()->id() : null)
                        ->searchable()
                        ->preload()
                        ->required(),
        
                    Forms\Components\TextInput::make('priority')
                        ->required()
                        ->label('Priority Level'),
        
                    Forms\Components\TextInput::make('pr_number')
                        ->label('PR Number')
                        ->required()
                        ->maxLength(255),
        
                    Forms\Components\DateTimePicker::make('required_by_date')
                        ->label('Required By Date')
                        ->required(),
        
                    Forms\Components\Textarea::make('comment')
                        ->label('Additional Notes / Comments')
                        ->required()
                        ->columnSpanFull(),
                ]),
        
            Forms\Components\Section::make('Approval and Status')
                ->description('People involved and approval status of the requisition.')
                ->schema([
                    Forms\Components\TextInput::make('status')
                        ->label('Request Status')
                        ->required(),
        
                    Forms\Components\TextInput::make('prepared_by_id')
                        ->label('Prepared By (User ID)')
                        ->required()
                        ->numeric(),
        
                    Forms\Components\DateTimePicker::make('prepared_dt')
                        ->label('Date Prepared'),
        
                    Forms\Components\TextInput::make('checked_by_id')
                        ->label('Checked By (User ID)')
                        ->required()
                        ->numeric(),
        
                    Forms\Components\DateTimePicker::make('checked_dt')
                        ->label('Date Checked'),
        
                    Forms\Components\TextInput::make('approved_by_id')
                        ->label('Approved By (User ID)')
                        ->required()
                        ->numeric(),
        
                    Forms\Components\DateTimePicker::make('approved_dt')
                        ->label('Date Approved'),
        
                    Forms\Components\TextInput::make('executed_by_id')
                        ->label('Executed By (User ID)')
                        ->numeric(),
        
                    Forms\Components\DateTimePicker::make('executed_at')
                        ->label('Date Executed'),
                ]),
            ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('requester_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('pr_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('required_by_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('prepared_by_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prepared_dt')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checked_by_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checked_dt')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_by_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_dt')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('executed_by_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('executed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseRequisitions::route('/'),
            'create' => Pages\CreatePurchaseRequisition::route('/create'),
            'view' => Pages\ViewPurchaseRequisition::route('/{record}'),
            'edit' => Pages\EditPurchaseRequisition::route('/{record}/edit'),
        ];
    }
}
