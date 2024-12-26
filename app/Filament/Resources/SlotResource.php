<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Slot;
use Filament\Tables;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SlotResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SlotResource\RelationManagers;

class SlotResource extends Resource
{
    protected static ?string $model = Slot::class;

    protected static ?string $navigationIcon = 'heroicon-s-clock';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('schedule_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('appointment_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_booked')
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule_id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->sortable()
                    ->time()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('H:i');
                    }),
                Tables\Columns\TextColumn::make('end_time')
                    ->sortable()
                    ->time()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('H:i');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_booked')
                    ->boolean()
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
            ->modifyQueryUsing(function (Builder $query): Builder {
                $user = Auth::user();

                if ($user->role == 'doctor') {
                    return $query->whereHas('schedule', function ($subQuery) use ($user) {
                        $subQuery->where('doctor_id', $user->doctor->id);
                    });
                }

                if ($user->role == 'admin') {
                    // Admin sees all records
                    return $query;
                }

                // Default: No records for unauthorized users
                return $query->whereRaw('0 = 1');
            })

            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            ->default(null),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!isset($data['date'])) {
                            return $query;
                        }
                        return $query
                            ->whereDate('date', '=', $data['date']);
                    })
            ])
            ->defaultSort('created_at', 'desc')

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('Book')
                    ->label('Book')
                    ->action(function ($record) {
                        $record->update([
                            'is_booked' => true,
                        ]);

                        Notification::make()
                            ->title('Slot Booked!')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-s-check-badge')
                    ->visible(fn($record) => !$record->is_booked && Auth::user()->role === 'doctor'),

                Action::make('Unbook')
                    ->label('Unbook')
                    ->action(function ($record) {
                        $record->update([
                            'is_booked' => false,
                        ]);

                        Notification::make()
                            ->title('Slot Unbooked!')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-s-x-circle')
                    ->visible(fn($record) => $record->is_booked && (Auth::user()->role === 'doctor' || Auth::user()->role === 'admin')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn(): bool => Auth::user()->role === 'admin'),
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
            'index' => Pages\ListSlots::route('/'),
            'create' => Pages\CreateSlot::route('/create'),
            'view' => Pages\ViewSlot::route('/{record}'),
            'edit' => Pages\EditSlot::route('/{record}/edit'),
        ];
    }
}
