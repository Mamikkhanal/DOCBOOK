<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaymentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaymentResource\RelationManagers;
use Filament\Forms\Components\Toggle;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-s-credit-card';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Patient Name')
                    ->getStateUsing(function ($record) {
                        return $record->appointment->patient->user->name;
                    })
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('appointment_id')
                ->label('Appointment')
                ->getStateUsing(function ($record) {
                    return $record->appointment->patient->user->name .' '. ' '.'to'.' '.' '. $record->appointment->doctor->user->name;
                }),
                Tables\Columns\TextColumn::make('service_id')
                ->label('Service')
                    ->getStateUsing(function ($record) {
                        return $record->service->name;
                    }),
                Tables\Columns\TextColumn::make('pid')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('amount') 
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(function ($record) {
                        if ($record) {
                            return $record->status === 'paid' ? 'success' : 'warning';
                        }
                        return 'warning';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query): Builder {
                $user = Auth::user();
            
                if ($user->role === 'admin') {
                    return $query;
                }
            
                if ($user->role === 'patient') {
                    return $query->where('user_id', $user->id);
                }
            
                // Default case: restrict query if no role matches
                return $query->whereNull('id'); // Return no results
            })
            ->defaultSort('created_at', 'desc')
            
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_at')
                            ->default(null),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!isset($data['created_at'])) {
                            return $query;
                        }
                        return $query
                            ->whereDate('created_at', '=', $data['created_at']);
                    })
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->hidden(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
