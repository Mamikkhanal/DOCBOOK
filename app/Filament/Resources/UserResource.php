<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Doctor;
use App\Models\Patient;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Specialization;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationGroup = 'Profiles';

    protected static ?string $navigationGroupIcon = 'heroicon-s-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                // Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
                Forms\Components\Select::make('role')
                    ->options([
                        'doctor' => 'Doctor',
                        'patient' => 'Patient',
                        'admin' => 'Admin',
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\Fieldset::make('patient')
                    ->label('Patient Details')
                    ->relationship('patient')
                    ->schema([
                        Forms\Components\TextInput::make('age')->required(),
                    ])
                    ->visible(fn($get) => $get('role') === 'patient'), // Show only if role is patient

                Forms\Components\Fieldset::make('doctor')
                    ->label('Doctor Details')
                    ->relationship('doctor')
                    ->schema([
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
                            }),
                    ])
                    ->visible(fn($get) => $get('role') === 'doctor'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
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
                    // Admin can see all records
                    return $query;
                }
            
                if ($user->role === 'doctor' || $user->role === 'patient') {
                    // Filter records to only the authenticated user's entries
                    return $query->where('id', $user->id);
                }
            
                return $query; // Default case, if needed
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
