<?php

namespace App\Filament\Resources;

use Carbon\Carbon;

use Filament\Forms;
use App\Models\Slot;
use App\Models\User;
use Filament\Tables;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Schedule;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Appointment;
use App\Models\Specialization;
use Faker\Provider\ar_EG\Text;
use Filament\Facades\Filament;
use PhpParser\Node\Stmt\Label;
use App\Services\OpenAIService;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Console\View\Components\Info;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\PaymentResource\Pages\StripePayment;
use App\Filament\Resources\AppointmentResource\RelationManagers;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-days';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Split::make([
                    Section::make([

                        Forms\Components\Select::make('patient_id')
                            ->required()
                            ->visible(Auth::user()->role === 'admin')
                            ->options(function () {
                                return User::whereHas('patient')->get()
                                    ->mapWithKeys(function ($user) {
                                        $label = $user->name && $user->patient->age
                                            ? "{$user->name} ----(Age:{$user->patient->age})"
                                            : null;

                                        return $label ? [$user->patient->id => $label] : [];
                                    })
                                    ->toArray();
                            })
                            ->label('Select Patient')
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('doctor_id')
                            ->required()
                            ->options(function () {
                                return User::whereHas('doctor')->get()
                                    ->mapWithKeys(function ($user) {
                                        $label = $user->name && $user->doctor->specialization->name
                                            ? "{$user->name} ({$user->doctor->specialization->name})"
                                            : null;

                                        return $label ? [$user->doctor->id => $label] : [];
                                    })
                                    ->toArray();
                            })
                            ->label('Select Doctor')
                            ->live()
                            ->reactive()
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('service_id')
                            ->required()
                            ->options(function (callable $get) {
                                return Service::all()->pluck('name', 'id');
                            })
                            ->label('Select Service')
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('schedule_id')
                            ->required()
                            ->options(function (callable $get) {
                                $doctorId = $get('doctor_id');
                                if (!$doctorId) {
                                    return [];
                                }

                                return Schedule::where('doctor_id', $doctorId)
                                    ->whereDate('date', '>=', Carbon::today())
                                    ->get()
                                    ->mapWithKeys(function ($schedule) {
                                        $formattedDate = Carbon::parse($schedule->date)->format('d-m-Y');
                                        return [$schedule->id => $formattedDate];
                                    })
                                    ->toArray();
                            })
                            ->live()
                            ->label('Select Schedule')
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('slot_id')
                            ->required()
                            ->options(function (callable $get) {
                                $scheduleId = $get('schedule_id');
                                if (!$scheduleId) {
                                    return [];
                                }

                                return Slot::where('schedule_id', $scheduleId)
                                    ->where('is_booked', false)
                                    ->whereTime('start_time', '>=', Carbon::now()->format('H:i'))
                                    ->get()
                                    ->mapWithKeys(function ($slot) {
                                        return [
                                            $slot->id => Carbon::parse($slot->start_time)->format('H:i'),
                                        ];
                                    })
                                    ->toArray();
                            })
                            ->label('Select Slot')
                            ->hiddenOn('edit'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->hiddenOn('edit'),

                        Forms\Components\FileUpload::make('prescription')
                            ->label('Prescription')
                            ->image()
                            ->disk('public')
                            ->required()
                            ->nullable()
                            ->previewable(true)
                            ->visible(Auth::user()->role === 'doctor'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'booked' => 'Booked',
                                'cancelled' => 'Cancelled',
                                'completed' => 'Completed',
                            ])
                            ->visibleOn('edit'),
                    ]),
                ]),

                Split::make([
                    Section::make([
                        // Problem Description Input
                        Forms\Components\TextInput::make('problem')
                            ->label('Describe Your Problem')
                            ->helperText('Describe your health problem in detail to get a specialization suggestion.'),

                        // Submit Button
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('Suggest')
                                ->action('suggestSpecialization')
                                ->color('primary'),
                        ]),

                    ]),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
            'stripePayment' => StripePayment::route('/stripe-payment/{record}'),

        ];
    }



}