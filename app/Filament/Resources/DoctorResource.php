<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Doctor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Specialization;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DoctorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DoctorResource\RelationManagers;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-s-user';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Profiles';


    protected static ?string $navigationGroupIcon = 'heroicon-s-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->disabled(true),
                Forms\Components\Select::make('specialization')
                    ->label('Specialization')
                    ->options(
                        Specialization::pluck('name', 'name') // Assuming you have a Specialization model with 'name' and 'id' columns
                    )
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Validate that the selected specialization is available in the database
                        $availableSpecializations = Specialization::pluck('name')->toArray();
                        if (!in_array($state, $availableSpecializations)) {
                            // You can add custom validation logic here, if needed
                            $set('specialization', null); // Clear the value if invalid
                        }
                    })
                    ->required(),
                Forms\Components\Toggle::make('is_available')
                    ->required()
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('specialization.name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean(),
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

                // Check if the user is an admin
                if ($user && $user->role === 'admin') {
                    // If admin, return all records
                    return $query;
                }

                // If not admin, filter by user_id
                return $query->where('user_id', $user?->id);
            })
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'view' => Pages\ViewDoctor::route('/{record}'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),

            // The bulk actions that are available for the table
        ];
        // The 'delete' bulk action will delete all selected doctors
    }
}
