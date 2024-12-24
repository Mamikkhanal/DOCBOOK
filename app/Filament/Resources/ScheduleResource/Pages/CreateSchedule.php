<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ScheduleResource;
use Illuminate\Validation\ValidationException;


class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        $doctorId = $data['doctor_id'];

        if (!$this->canCreateSchedule($data, $doctorId)) {
            // Send notification
            Notification::make()
                ->title('Schedule Overlap')
                ->body('The schedule overlaps with an existing schedule.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'start_time' => 'The schedule overlaps with an existing schedule.',
            ]);
        }

        // Create the schedule first
        $schedule = parent::handleRecordCreation($data);

        // Generate the 10-minute time slots for the doctor
        $this->createTimeSlots($data['start_time'], $data['end_time'], $schedule->id);

        return $schedule;
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.schedules.index');
    }

    // protected function afterCreate($record)
    // {

    // }

    protected function canCreateSchedule($data, $doctorId)
    {
        $existingSchedules = Schedule::where('doctor_id', $doctorId)->get();

        if (empty($existingSchedules)) {
            return true;
        };

        foreach ($existingSchedules as $schedule) {

            $schedule_start_time = Carbon::parse($schedule->start_time);
            $schedule_end_time = Carbon::parse($schedule->end_time);

            $data_start_time = Carbon::parse($data['start_time']);
            $data_end_time = Carbon::parse($data['end_time']);

            if (Carbon::parse($schedule->date)->format('d-m-Y') == Carbon::parse($data['date'])->format('d-m-Y')) {
                if (
                    ($data_start_time)->between($schedule_start_time, $schedule_end_time) ||
                    ($data_end_time)->between($schedule_start_time, $schedule_end_time) ||
                    ($schedule_start_time)->between($data_start_time, $data_end_time) ||
                    ($schedule_end_time)->between($data_start_time, $data_end_time)
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function createTimeSlots($startTime, $endTime, $scheduleId)
    {
        $startTime = Carbon::parse($startTime);
        $endTime = Carbon::parse($endTime);

        // Create time slots of 10 minutes
        while ($startTime < $endTime) {
            // Create a time slot
            if ($startTime->addMinutes(10) > $endTime) {
                break;
            }
            $slot = Slot::create([
                'schedule_id' => $scheduleId,
                'date' => Schedule::find($scheduleId)->date,
                'start_time' => $startTime->format('H:i'),  // Store start time in 'H:i' format
                'end_time' => $startTime->addMinutes(10)->format('H:i'), // Add 10 minutes for the end time
            ]);
        }
    }
}
