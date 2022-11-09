<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\TypeUser;
use App\Models\User;
use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ScheduleService
{
    public function get(int $id)
    {
        $schedule = Schedule::find($id);

        if(!$schedule) {
            throw ValidationException::withMessages(['Agenda não encontrada.']);
        }

        $schedule->users;
        $schedule->schedulings;

        return $schedule;
    }


    public function getAllAvailable(string $ongsIds)
    {
        $schedules = Schedule::where('available', true)
            ->with('users')
            ->with('users.ongs')
            ->whereRelation('users.ongs',
                function (Builder $query) use ($ongsIds) {
                    $query->where('ongs.id', '=', $ongsIds);
                })
            ->orderBy('start_time')
            ->get()
            ->toArray();

        if(!$schedules) {
            throw ValidationException::withMessages(['Não há ONGs disponíveis.']);
        }

        return $schedules;
    }


    public function store(array $scheduleData)
    {
        $arrayDate = $this->defineArrayDate($scheduleData['start_date'], $scheduleData['end_date']);
        $arraySchedule = $this->defineArraySchedule($scheduleData['start_time'], $scheduleData['end_time'], $scheduleData['interval']);
        $response = [];

        try
        {
            $this->verifyUserBelongsToOng($scheduleData['users_ids']);

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


    public function update(int $id, Request $request)
    {
        $schedule = Schedule::find($id);

        if(!!!$schedule) {
            return response()->json(['error' => 'Agenda não encontrada.'], 404);
        }

        try  {
            DB::beginTransaction();
                $schedule->date = Carbon::parse($request->input('date'));
                $schedule->start_time = Carbon::createFromTimeString($request->input('start_time'));
                $schedule->end_time = Carbon::createFromTimeString($request->input('end_time'));
                $schedule->available = $request->input('available');
                $schedule->save();
            DB::commit();

            return response()->json(['data' => ['status' => 'Atualizado com sucesso!']], 200);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    private function verifyUserBelongsToOng(array $usersIds)
    {
        foreach($usersIds as $userId)
        {
            $user = User::whereId($userId)->where('type_user_id', TypeUser::TYPE_ONG)->get()->toArray();

            if(!$user) {
                throw ValidationException::withMessages(['Usuário não encontrado ou não atua em ONGs.']);
            }
        }

        return true;
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
