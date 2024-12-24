<?php

namespace App\Filament\Resources;

use Exception;
use Filament\Forms;
use Filament\Tables;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Patient;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use Faker\Provider\ar_EG\Text;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\ReviewResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReviewResource\RelationManagers;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-text';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('appointment_id')
                    ->required()
                    ->default(request()->query('appointment_id')),
                
                Forms\Components\Textarea::make('review')
                    ->required()
                    ->columnSpanFull()
                    ->label('Review')
                    ->default('Good service. I will recommend to others.'),

                Forms\Components\Textarea::make('message')
                    ->columnSpanFull()
                    ->disabled( fn () => !request()->query('appointment_id'))
                    ->visible(fn () => !request()->query('appointment_id'))
                    ->default('You must visit through the appointment to create review'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_id')
                ->label('Appointment')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('review')
                ->label('Review'),
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
            
                if ($user->role=='patient') {
                    // Filter for patients
                    return $query->whereHas('appointment.patient.user', function ($subQuery) use ($user) {
                        $subQuery->where('id', $user->id);
                    });
                }
            
                if ($user->role=='doctor') {
                    // Filter for doctors
                    return $query->whereHas('appointment.doctor.user', function ($subQuery) use ($user) {
                        $subQuery->where('id', $user->id);
                    });
                }
            
                if ($user->role=='admin') {
                    // Admin sees all records
                    return $query;
                }
            
                // Default: No records for unauthorized users
                return $query->whereRaw('0 = 1');
            })
            
            

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


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Section::make('Appointment')
                ->schema([
                    
                    TextEntry::make('appointment_id')
                    ->label('Appointment ID'),
    
                    TextEntry::make('Doctor')
                    ->getStateUsing(function ($record) {
                        $doctor = Doctor::find($record->appointment->doctor_id);
                        return $doctor ? $doctor->user->name : 'Unknown'; 
                    }),
    
                    TextEntry::make('Patient')
                    ->getStateUsing(function ($record) {
                        $patient = Patient::find($record->appointment->patient_id);
                        return $patient ? $patient->user->name : 'Unknown';
                    }),
                ])
                ->columns(3),

                Section::make('Review')
                ->schema([ 
                    TextEntry::make('review')
                    ->label(''),
                ])
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
