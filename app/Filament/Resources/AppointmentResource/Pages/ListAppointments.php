<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\User;
use Filament\Actions;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Schedule;
use Filament\Tables\Table;
use App\Models\Appointment;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\AppointmentResource;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_id')
                    ->label('Patient Name') // Change the label to reflect the displayed value
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $patient = Patient::find($state);
                        return $patient ? $patient->user->name : 'Unknown'; // Return name or fallback
                    })
                    ->hidden(Auth::user()->role === 'patient'),

                TextColumn::make('doctor_id')
                    ->label('Doctor Name') // Change the label to reflect the displayed value
                    ->sortable()
                    ->formatStateUsing(function ($state) {

                        $doctor = Doctor::find($state);
                        return $doctor ? $doctor->user->name : 'Unknown'; // Return name or fallback
                    })
                    ->hidden(Auth::user()->role === 'doctor'),

                TextColumn::make('service_id')
                    ->label('Service') // Change the label to reflect the displayed value
                    ->sortable()
                    ->formatStateUsing(function ($state) {

                        $service = Service::find($state);
                        return $service ? $service->name : 'Unknown'; // Return name or fallback
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'booked' => 'success',
                            'pending' => 'gray',
                            'cancelled' => 'primary',
                            'completed' => 'success',
                        };
                    }),

                TextColumn::make('schedule_id')
                    ->label('Date')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        $schedule = Schedule::find($state);
                        return $schedule ? Carbon::parse($schedule->date)->format('d-m-Y') : 'Unknown';
                    }),

               TextColumn::make('slot_id')
                    ->label('Start Time')
                    ->sortable()
                    ->time()
                    ->formatStateUsing(function ($state) {
                        $slot = Slot::find($state);
                        return $slot ? Carbon::parse($slot->start_time)->format('g:i A') : 'Unknown';
                    }),




                TextColumn::make('payment_status')
                    ->label('Fee')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->payment) {
                            return $record->payment->status === 'paid' ? 'success' : 'primary';
                        }
                        return 'warning';
                    })
                    ->getStateUsing(function ($record) {
                        return Payment::where('appointment_id', $record->id)->exists() && $record->payment->status === 'paid' ? 'Paid' : 'Unpaid';
                    }),


                TextColumn::make('description')
                    ->label('Description')
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('prescription')
                    ->label('Prescription')
                    ->getStateUsing(function ($record) {
                        return $record->prescription
                            ? asset('storage/' . $record->prescription) // Correct path to storage/public
                            : asset('images/logo.png'); // Fallback image
                    })
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            ->defaultSort('created_at', 'desc')

            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            ->default(null),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['date'])) {
                            return $query;
                        }
                        return $query
                            ->whereHas('schedule', function ($schedulequery) use ($data) {
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
                            fn($query) => $query->where('status', $data['value'])
                        );
                    }),


            ])


            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('primary'),
                    EditAction::make()
                        ->color('primary'),
                    DeleteAction::make()->hidden(fn($record) => $record->status === 'completed' || $record->status === 'booked' && Auth::user()->role === 'patient')
                        ->action(function ($record) {
                            $record->slot->is_booked = false;
                            $record->slot->save();
                            $record->delete();
                        })
                        ->color('primary'),

                    Action::make('Cancel')
                        ->label('Cancel')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'cancelled',
                            ]);

                            // Mail::to($record->patient->user->email)->send(new \App\Mail\CancelledMail($record));
                            $record->slot->is_booked = false;
                            $record->slot->save();
                            Notification::make()
                                ->title('Appointment Cancelled!')
                                ->success()
                                ->send()
                                ->sendToDatabase($record->patient->user);
                        })
                        ->requiresConfirmation()
                        ->color('primary')
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->hidden(fn($record) => $record->status === 'cancelled' || $record->status === 'booked' || $record->status === 'completed' || Auth::user()->role == 'patient' ?? true),

                    Action::make('Book')
                        ->label('Book')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'booked',
                            ]);
                            // Mail::to($record->patient->user->email)->send(new \App\Mail\BookedMail($record));
                            if (!$record->payment) {
                                Payment::create([
                                    'amount' => Service::find($record->service_id)->price,
                                    'appointment_id' => $record->id,
                                    'service_id' => $record->service_id,
                                    'user_id' => $record->patient->user_id,
                                    'status' => 'unpaid',
                                ]);
                            }

                            Notification::make()
                                ->title('Appointment Booked!')
                                ->success()
                                ->send()
                                ->sendToDatabase($record->patient->user);
                        })
                        ->requiresConfirmation()
                        ->color('gray')
                        ->icon('heroicon-s-check-badge')
                        ->hidden(fn($record) => $record->status === 'booked' || $record->status === 'completed' || $record->status === 'cancelled' || Auth::user()->role == 'patient' ?? true),

                    Action::make('Complete')
                        ->label('Complete')
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'completed',
                            ]);

                            Notification::make()
                                ->title('Appointment Completed!')
                                ->success()
                                ->send()
                                ->sendToDatabase($record->patient->user);
                        })
                        ->requiresConfirmation()
                        ->color('gray')
                        ->icon('heroicon-s-arrow-up-on-square')
                        ->hidden(fn($record) => $record->status === 'pending' || $record->status === 'completed' || $record->status === 'cancelled' || (!$record->payment || $record->payment->status === 'unpaid') || Auth::user()->role == 'patient' ?? true),

                    Action::make('Payment')
                        ->label('Pay via Esewa')
                        ->action(function ($record) {
                            return redirect(route('payment.pay', ['id' => $record->payment->id]));
                        })
                        ->color('gray')
                        ->icon('heroicon-s-credit-card')
                        ->visible(fn($record) => $record->payment->status === 'unpaid' ?? false),

                    Action::make('SPayment')
                        ->label('Pay via Stripe')
                        ->action(function ($record) {
                            // $url = url('docbook/appointments/stripe-payment/{record}', ['id'=> $record->payment->id]);
                            // return $url;
                            return redirect(route('filament.admin.resources.appointments.stripePayment', ['record' => $record->id]));
                        })
                        ->color('gray')
                        ->icon('heroicon-s-credit-card')
                        ->visible(fn($record) => $record->payment->status === 'unpaid' ?? false),
                    // ->visible(fn($record) => $record->status == 'pending' || $record->status === 'booked' || ($record->status === 'booked' && $record->payment->status === 'unpaid')),

                    Action::make('Give_Review')
                        ->label('Give a Review')
                        ->action(function ($record) {
                            return redirect(route('filament.admin.resources.reviews.create', ['appointment_id' => $record->id]));
                        })
                        ->color('primary')
                        ->icon('heroicon-s-chat-bubble-left-ellipsis')
                        ->visible(fn($record) => $record->status === 'completed' && Auth::user()->role === 'patient' && !Review::where('appointment_id', $record->id)->exists()),

                    Action::make('View_Review')
                        ->label('View Review')
                        ->action(function ($record) {
                            $review = Review::where('appointment_id', $record->id)->first();
                            return redirect(route('filament.admin.resources.reviews.view', ['record' => $review->id]));
                        })
                        ->color('primary')
                        ->icon('heroicon-s-chat-bubble-left-ellipsis')
                        ->visible(fn($record) => $record->status === 'completed' && Auth::user()->role === 'patient' && Review::where('appointment_id', $record->id)->exists()),



                ])->label('Actions')
                    ->icon('heroicon-s-bars-arrow-down')
                    ->size(ActionSize::Small)
                    ->color('action_btn')
                    ->button(),

            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden(),
                ]),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->badge(fn() => $this->getCount())
                ->badgeColor('primary'),

            
                Tab::make('Upcoming')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d'));
                    });
                    $count= count($query->get());
                })
                
                ->badge(function() {
                    if(Auth::user()->role == 'patient') {
                        return Appointment::where('patient_id', Auth::user()->patient->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d')); 
                        })
                        ->count();
                    }
                    elseif(Auth::user()->role == 'doctor') {
                        return Appointment::where('doctor_id', Auth::user()->doctor->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d'));
                        })
                        ->count();
                    }
                    return Appointment::whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d'));
                    })->count();
                })
                ->badgeColor('primary'),

            Tab::make('Today')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d'));
                    });
                })
                ->badge(function() {
                    if(Auth::user()->role == 'patient') {
                        return Appointment::where('patient_id', Auth::user()->patient->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d')); 
                        })
                        ->count();
                    }
                    elseif(Auth::user()->role == 'doctor') {
                        return Appointment::where('doctor_id', Auth::user()->doctor->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d'));
                        })
                        ->count();
                    }
                    return Appointment::whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d'));
                    })->count();
                })
                ->badgeColor('primary'),

            Tab::make('Pending')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'pending');
                })
                ->badge(fn() => $this->getCount('pending'))
                ->badgeColor('primary'),

            Tab::make('Booked')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'booked');
                })
                ->badge(fn() => $this->getCount('booked'))
                ->badgeColor('primary'),

            Tab::make('Completed')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'completed');
                })
                ->badge(fn() => $this->getCount('completed'))
                ->badgeColor('success'),

            Tab::make('Cancelled')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'cancelled');
                })
                ->badge(fn() => $this->getCount('cancelled'))
                ->badgeColor('primary'),


        ];
    }

    protected function getCount(string $status = null): int
    {
        $query = Appointment::query();

        if (Auth::user()->role == 'patient') {
            $query->where('patient_id', Auth::user()->patient->id);
        } elseif (Auth::user()->role == 'doctor') {
            $query->where('doctor_id', Auth::user()->doctor->id);
        } elseif (Auth::user()->role == 'admin') {
            $query = Appointment::query();
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }
}
