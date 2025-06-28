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
use Filament\Forms\Set;
use Filament\Forms\Get;

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
                        ->live(debounce: '1000')
                        ->afterStateUpdated(function(Set $set, ?string $state){
                            $set('prepared_by_id', $state);
                        })
                        ->required(),
        
                    Forms\Components\Select::make('priority')
                        ->required()
                        ->label('Priority Level')
                        ->options([
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                            'urgent' => 'Urgent'
                        ]),
                    Forms\Components\TextInput::make('pr_number')
                        ->label('PR Number')
                        ->required()
                        ->maxLength(255),
        
                    Forms\Components\DateTimePicker::make('required_by_date')
                        ->label('Required By Date')
                        ->native(false)
                        ->required(),
        
                    Forms\Components\RichEditor::make('comment')
                        ->label('Additional Notes / Comments')
                        ->required()
                        ->disableToolbarButtons([
                            'attachFiles'
                        ])
                        ->columnSpanFull(),
                ]),
        
            Forms\Components\Section::make('Approval and Status')
                ->description('People involved and approval status of the requisition.')
                ->schema([
                    Forms\Components\Radio::make('status')
                        ->label('Request Status')
                        ->options([
                            'draft' => 'Draft',
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'cancelled' => 'Cancelled',
                            'completed' => 'Completed'
                        ])
                        ->inline()
                        ->default('draft')
                        ->required(),
        
                    Forms\Components\Select::make('prepared_by_id')
                        ->label('Prepared By (User ID)')
                        ->searchable()
                        ->preload()
                        ->relationship('preparer', 'name', fn($query) =>  $query->when(
                            !auth()->user()->hasRole('super_admin'),
                            fn ($query) => $query->whereHas('roles', fn ($q) =>
                                $q->whereIn('name', ['member'])
                            )
                        ))
                        ->required(),
        
        
                    Forms\Components\Select::make('checked_by_id')
                        ->label('Supervisor / Manager By (User ID)')
                        ->relationship('checker', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->whereIn('name', ['checker'])))
                        ->searchable()
                        ->preload()
                        ->required(),
        
                    Forms\Components\Select::make('approved_by_id')
                        ->label('General Manager By (User ID)')
                        ->relationship('mansor', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->whereIn('name', ['manager', 'supervisor'])))
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('executed_by_id')
                        ->label('Executive Manager By (User ID)')
                        ->relationship('executive', 'name', fn($query) => $query->whereHas('roles', fn($q) => $q->whereIn('name', ['executive'])))
                        ->searchable()
                        ->preload()
                        ->required(),
            ]),
            Forms\Components\Section::make('Files & Documents')
                ->description('Kindly upload your PR document along with any other relevant files.')
                ->schema([
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Attach PR File(s)')
                        ->multiple()
                        ->directory('PR')
                        ->preserveFilenames()
                        ->reorderable()
                        ->downloadable()
                        ->panelLayout('grid')
                        ->openable()
                        ->previewable(true)
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

                    
                Tables\Columns\TextColumn::make('priority')
                
                ,
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
            ])
            ->paginated(true);
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
