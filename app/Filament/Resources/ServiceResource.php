<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Customer;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Buat layanan')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->maxLength(65535)
                            ->columnSpanFull()
                    ]),
                Section::make('Buat fitur')
                    ->description('Fitur - Fitur yang dibuat untuk layanan anda')
                    ->schema([
                        Repeater::make('features')
                            ->relationship('features')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Fitur')
                                    ->maxLength(255)
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Fitur')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Fitur')
                                    ->numeric()
                                    ->required(),
                            ])->columns(2)->addActionLabel('Tambah Fitur')->required()
                    ])->collapsed()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('features.name')
                    ->label('Fitur yang dibuat'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('add_invoice')
                    ->label('Buat Invoice')
                    ->form([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->options(Customer::pluck('name','id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->action(function (Service $service, array $data){
                        $service->createInvoice($data['customer_id']);
                    }),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
