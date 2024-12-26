<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ScheduleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ScheduleResource\RelationManagers;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('doctor_id')
                    ->required()
                    ->default(fn() => Auth::user()?->doctor?->id),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->minDate(today()),
                Forms\Components\TimePicker::make('start_time')
                    ->required()
                    ->afterOrEqual(fn(callable $get) => $get('date') == now()->toDateString() ? now()->format('H:i') : null)
                    ->seconds(false),
                Forms\Components\TimePicker::make('end_time')
                    ->required()
                    ->after('start_time')
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('schedule_id')
                //     ->numeric()
                //     ->sortable()
                //     ->label('Schedule'),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->time()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('H:i');
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->time()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('H:i');
                    })
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
            ->modifyQueryUsing(
                fn(Builder $query): Builder => $query->where('doctor_id', Auth::user()?->doctor?->id)
            )
            
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn(): bool => Auth::user()->role === 'doctor'),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
