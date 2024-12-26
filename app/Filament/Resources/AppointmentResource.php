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
use Faker\Provider\ar_EG\Text;
use Filament\Facades\Filament;
use PhpParser\Node\Stmt\Label;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Console\View\Components\Info;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\PaymentResource\Pages\StripePayment;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use Filament\Tables\Filters\SelectFilter;

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
                                        $label = $user->name && $user->doctor->specialization
                                            ? "{$user->name} ({$user->doctor->specialization})"
                                            : null;

                                        return $label ? [$user->doctor->id => $label] : [];
                                    })
                                    ->toArray();
                            })
                            ->label('Select Doctor')
                            ->live()
                            ->reactive(),

                        Forms\Components\Select::make('service_id')
                            ->required()
                            ->options(function (callable $get) {
                                return Service::all()->pluck('name', 'id');
                            })
                            ->label('Select Service'),

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
                            ->label('Select Schedule'),

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
                        Forms\Components\Placeholder::make('schedules')
                        ->label('Schedules')
                        ->content(function ($get) {
                            $doctorId = $get('doctor_id');
                            if (!$doctorId) {
                                return 'No doctor selected.';
                            }
    
                            $schedules = Schedule::where('doctor_id', $doctorId)->get();
    
                            if ($schedules->isEmpty()) {
                                return 'No schedules available for this doctor.';
                            }
    
                            $schedulesData = $schedules->map(function ($schedule) {
                                return [
                                    'date' => $schedule->date,
                                    'start_time' => $schedule->start_time,
                                    'end_time' => $schedule->end_time,
                                ];
                            })->values()->toArray();
    
                            return view('filament.forms.list', [
                                'columns' => ['day', 'time', 'status'],
                                'rows' => $schedulesData,
                            ]);
                        })
                        ->columnSpanFull(),
                    ]),

                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient_id')
                    ->label('Patient Name') // Change the label to reflect the displayed value
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $patient = Patient::find($state);
                        return $patient ? $patient->user->name : 'Unknown'; // Return name or fallback
                    })
                    ->hidden(Auth::user()->role === 'patient'),

                Tables\Columns\TextColumn::make('doctor_id')
                    ->label('Doctor Name') // Change the label to reflect the displayed value
                    ->sortable()
                    ->formatStateUsing(function ($state) {

                        $doctor = Doctor::find($state);
                        return $doctor ? $doctor->user->name : 'Unknown'; // Return name or fallback
                    })
                    ->hidden(Auth::user()->role === 'doctor'),

                Tables\Columns\TextColumn::make('service_id')
                    ->label('Service') // Change the label to reflect the displayed value
                    ->sortable()
                    ->formatStateUsing(function ($state) {

                        $service = Service::find($state);
                        return $service ? $service->name : 'Unknown'; // Return name or fallback
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('schedule_id')
                    ->label('Date')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $schedule = Schedule::find($state);
                        return $schedule ? Carbon::parse($schedule->date)->format('d-m-Y') : 'Unknown';
                    }),

                Tables\Columns\TextColumn::make('slot_id')
                    ->label('Start Time')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $slot = Slot::find($state);
                        return $slot ? Carbon::parse($slot->start_time)->format('' . 'H:i') : 'Unknown';
                    }),


                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'booked' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            'completed' => 'success',
                        };
                    }),


                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Fee')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->payment) {
                            return $record->payment->status === 'paid' ? 'success' : 'warning';
                        }
                        return 'warning';
                    })
                    ->getStateUsing(function ($record) {
                        return Payment::where('appointment_id', $record->id)->exists() && $record->payment->status === 'paid' ? 'Paid' : 'Unpaid';
                    }),


                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('prescription')
                    ->label('Prescription')
                    ->getStateUsing(function ($record) {
                        return $record->prescription
                            ? asset('storage/' . $record->prescription) // Correct path to storage/public
                            : asset('images/logo.png'); // Fallback image
                    })
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->modifyQueryUsing(function (Builder $query) {
                // Get the currently authenticated user
                $user = User::find(Filament::auth()->user()->id);
                if (!$user) {
                    return $query->whereRaw('1 = 0'); // Return no results if no user is authenticated
                }

                // If the user is an admin, they can see all appointments
                if ($user->hasRole('admin')) {
                    return $query;
                }

                // If the user is a doctor, only their appointments are shown
                if ($user->hasRole('doctor')) {
                    return $query->where('doctor_id', $user->doctor->id);
                }

                // If the user is a patient, only their appointments are shown
                if ($user->hasRole('patient')) {

                    if ($user->patient) {
                        return $query->where('patient_id', $user->patient->id);
                    } else {
                        return $query->whereRaw('1 = 0'); // If no patient relationship, show no appointments
                    }
                }

                // Default to no results if the role doesn't match (optional, you can adjust this)
                return $query->whereRaw('1 = 0');
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
                            ->whereHas('schedule', function ($schedulequery) use ($data) 
                            {
                                 $schedulequery->where('date', '=', $data['date']);
                            });
                    }),
                
                    SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'booked' => 'Booked',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])
                    ->label('Status')
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            !empty($data['value']),
                            fn ($query) => $query->where('status', $data['value'])
                        );
                    }),
                

            ])


            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()->hidden(fn($record) => $record->status === 'completed' || $record->status === 'booked' && Auth::user()->role === 'patient')
                        ->action(function ($record) {
                            $record->slot->is_booked = false;
                            $record->slot->save();
                            $record->delete();
                        }),

                    Action::make('Payment')
                        ->label('Pay')
                        ->action(function ($record) {
                            return redirect(route('payment.pay', ['id' => $record->payment->id]));
                        })
                        ->color('warning')
                        ->icon('heroicon-s-credit-card')
                        ->visible(fn($record) => $record->status=='pending'|| $record->status === 'booked' || ($record->status === 'booked' && $record->payment->status === 'unpaid')),

                    Action::make('SPayment')
                        ->label('Pay via Stripe')
                        ->action(function ($record) {
                            // $url = url('docbook/appointments/stripe-payment/{record}', ['id'=> $record->payment->id]);
                            // return $url;
                            return redirect(route('filament.admin.resources.appointments.stripePayment', ['record' => $record->id]));
                        })
                        ->color('success')
                        ->icon('heroicon-s-credit-card')
                        ->visible(fn($record) => $record->status=='pending' || $record->status === 'booked' || ($record->status === 'booked' && $record->payment->status === 'unpaid')),

                    Action::make('Give_Review')
                        ->label('Give a Review')
                        ->action(function ($record) {
                            return redirect(route('filament.admin.resources.reviews.create', ['appointment_id' => $record->id]));
                        })
                        ->color('warning')
                        ->icon('heroicon-s-chat-bubble-left-ellipsis')
                        ->visible(fn($record) => $record->status === 'completed' && Auth::user()->role === 'patient' && !Review::where('appointment_id', $record->id)->exists()),

                    Action::make('View_Review')
                        ->label('View Review')
                        ->action(function ($record) {
                            $review = Review::where('appointment_id', $record->id)->first();
                            return redirect(route('filament.admin.resources.reviews.view', ['record' => $review->id]));
                        })
                        ->color('warning')
                        ->icon('heroicon-s-chat-bubble-left-ellipsis')
                        ->visible(fn($record) => $record->status === 'completed' && Auth::user()->role === 'patient' && Review::where('appointment_id', $record->id)->exists()),

                    Action::make('Book')
                        ->label('Book')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'booked',
                            ]);

                            Payment::create([
                                'amount' => Service::find($record->service_id)->price,
                                'appointment_id' => $record->id,
                                'service_id' => $record->service_id,
                                'user_id' => $record->patient->user_id,
                                'status' => 'unpaid',
                            ]);

                            Notification::make()
                                ->title('Appointment Booked!')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-s-check-badge')
                        ->hidden(fn($record) => $record->status === 'booked' || $record->status === 'completed' || $record->status === 'cancelled' || Auth::user()->role == 'patient'),

                    Action::make('Complete')
                        ->label('Complete')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'completed',
                            ]);

                            Notification::make()
                                ->title('Appointment Completed!')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-s-arrow-up-on-square')
                        ->hidden(fn($record) => $record->status === 'pending' || $record->status === 'completed' || $record->status === 'cancelled' || (!$record->payment || $record->payment->status === 'unpaid') || Auth::user()->role == 'patient'),

                    Action::make('Cancel')
                        ->label('Cancel')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'cancelled',
                            ]);

                            $record->slot->is_booked = false;
                            $record->slot->save();
                            Notification::make()
                                ->title('Appointment Cancelled!')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->hidden(fn($record) => $record->status === 'cancelled'  || $record->status === 'completed' || Auth::user()->role == 'patient'),



                ])->label('Actions')
                    ->icon('heroicon-s-bars-arrow-down')
                    ->size(ActionSize::Small)
                    ->color('action_btn')
                    ->button(),

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('patient_id')
                    ->label('Patient Name')
                    ->getStateUsing(function ($record) {
                        $patient = Patient::find($record->patient_id);
                        return $patient ? $patient->user->name : 'Unknown'; // Return name or fallback
                    }),

                TextEntry::make('doctor_id')
                    ->label('Doctor Name')
                    ->getStateUsing(function ($record) {
                        $doctor = Doctor::find($record->doctor_id);
                        return $doctor ? $doctor->user->name : 'Unknown'; // Return name or fallback
                    }),

                TextEntry::make('service_id')
                    ->label('Service')
                    ->getStateUsing(function ($record) {
                        $service = Service::find($record->service_id);
                        return $service ? $service->name : 'Unknown'; // Return name or fallback
                    }),

                TextEntry::make('schedule_id')
                    ->label('Date')
                    ->getStateUsing(function ($record) {
                        $schedule = Schedule::find($record->schedule_id);
                        return $schedule ? Carbon::parse($schedule->date)->format('d-m-Y') : 'Unknown';
                    }),

                TextEntry::make('slot_id')
                    ->label('Start Time')
                    ->getStateUsing(function ($record) {
                        $slot = Slot::find($record->slot_id);
                        return $slot ? Carbon::parse($slot->start_time)->format('' . 'H:i') : 'Unknown';
                    }),

                TextEntry::make('slot_id')
                    ->label('End Time')
                    ->getStateUsing(function ($record) {
                        $slot = Slot::find($record->slot_id);
                        return $slot ? Carbon::parse($slot->end_time)->format('' . 'H:i') : 'Unknown';
                    }),

                TextEntry::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return $record->status;
                    }),

                TextEntry::make('description')
                    ->label('Description')
                    ->getStateUsing(function ($record) {
                        return $record->description;
                    }),

                ImageEntry::make('prescription')
                    ->label('Prescription')
                    ->getStateUsing(function ($record) {
                        return $record->prescription
                            ? asset('storage/' . $record->prescription)
                            : asset('images/logo.png');
                    })
                    ->height('auto')
                    ->width(700),
            ]);
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
