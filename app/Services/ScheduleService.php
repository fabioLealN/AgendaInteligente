<?php

namespace App\Services;

use App\Models\Schedule;
use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScheduleService
{
    public function store(array $scheduleData)
    {
        $arrayDate = $this->defineArrayDate($scheduleData['start_date'], $scheduleData['end_date']);
        $arraySchedule = $this->defineArraySchedule($scheduleData['start_time'], $scheduleData['end_time'], $scheduleData['interval']);
        $response = [];

        try
        {
            DB::beginTransaction();

            foreach($arrayDate as $date) {
                if($this->verifyDaysOfWeek($date, $scheduleData['days_week'])) {
                    foreach($arraySchedule as $schedule) {
                        $schedule = Schedule::create([
                            'date' => $date,
                            'start_time' => $schedule['start_schedule'],
                            'end_time' => $schedule['end_schedule'],
                            'interval_time' => Carbon::createFromTimeString($scheduleData['interval']),
                            'available' => true,
                        ]);

                        $schedule->users()->attach($scheduleData['users_ids']);
                        array_push($response, $schedule);
                    }
                }
            }

            DB::commit();
        }
        catch (BadMethodCallException | ValidationException $e)
        {
            DB::rollback();
            throw ValidationException::withMessages([$e->getMessage()]);
        }

        return response()->json(['data' => ['schedules' => $response]], 201);
    }


    private function verifyDaysOfWeek($date, $daysWeek)
    {
        if(is_null($daysWeek)) {
            return true;
        }

        return in_array($date->locale('pt_br')->shortDayName, $daysWeek);
    }


    private function defineArraySchedule($startTimeRange, $endTimeRange, $interval)
    {
        $finalDay = Carbon::createFromTimeString($endTimeRange);
        $stopLoop = true;
        $arraySchedule = [];

        do {
            $initialDay = Carbon::createFromTimeString($startTimeRange);
            $finalSchedule = $initialDay->addMinutes($this->transformTimeToMinutes($interval));

            if ($finalSchedule <= $finalDay) {
                $start = Carbon::createFromTimeString(
                        $finalSchedule->subMinutes($this->transformTimeToMinutes($interval))->toTimeString()
                    );

                $end = Carbon::createFromTimeString(
                        $finalSchedule->addMinutes($this->transformTimeToMinutes($interval))->toTimeString()
                    );

                array_push($arraySchedule, [
                    'start_schedule' => $start,
                    'end_schedule' => $end,
                ]);

                $startTimeRange = $end->toTimeString();

            } else {
                $stopLoop = false;
            }
        } while($stopLoop);

        return $arraySchedule;
    }


    private function defineArrayDate($startDateRange, $endDateRange)
    {
        $initialDate = Carbon::parse($startDateRange);
        $finalDate = Carbon::parse($endDateRange);
        $countDays = $initialDate->diffInDays($finalDate);
        $arrayDate = [];

        for($day = 0; $day <= $countDays; $day++) {
            $initialDate = Carbon::parse($startDateRange);
            $date = $initialDate->addDays($day);

            array_push($arrayDate, $date);
        }

        return $arrayDate;
    }


    private function transformTimeToMinutes($time)
    {
        $parts = explode(':', $time);

        return $parts[0]*60 + $parts[1];
    }
}
