<?php

namespace App\Services;

use App\Models\Ong;
use App\Models\Schedule;
use App\Models\TypeUser;
use App\Models\User;
use BadMethodCallException;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


    public function getAllAvailable(string $ongId)
    {
        $ong = Ong::find($ongId);
        $schedules = $ong->specialists->load(['schedules']);

        // $schedules = Schedule::where('available', true)
        //     ->with()
        //     ->whereRelation('specialists.ongs',
        //         function (Builder $query) use ($ongId) {
        //             $query->where('ongs.id', '=', $ongId);
        //         })
        //     ->orderBy('start_time')
        //     ->get()
        //     ->toArray();

        if(!$schedules) {
            throw ValidationException::withMessages(['Não há ONGs disponíveis.']);
        }

        return $schedules;
    }


    public function store(array $scheduleData)
    {
        $period = CarbonPeriod::create($scheduleData['start_date'], $scheduleData['final_date']);
        $times = CarbonPeriod::since($scheduleData['start_time'])
                    ->minutes($scheduleData['duration'])
                    ->until($scheduleData['final_time'])
                    ->toArray();

        $schedules = [];

        foreach ($period as $date) {
            $data['date'] = $date->format('Y-m-d');
            foreach ($times as $time) {
                DB::beginTransaction();

                $data['start_time'] = $time->format('H:i');

                if (next($times)) {
                    $data['end_time'] = Carbon::parse(current($times))->format('H:i');
                    $data['available'] = true;

                    $schedule =  Schedule::create($data);
                    $schedule->specialists()->attach($scheduleData['specialists_ids']);
                    $schedule->sizes()->attach($scheduleData['sizes_ids']);
                    $schedule->save();

                    array_push($schedules, $schedule);
                }

                DB::commit();

            }

        }

        return $schedules;
        // return response()->json(['data' => $schedules ], 201);
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
                $schedule->end_time = Carbon::createFromTimeString($request->input('final_time'));
                $schedule->sizes()->sync($request->input('sizes_ids'));
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


    private function defineArraySchedule($startTimeRange, $endTimeRange, $duration)
    {
        $finalDay = Carbon::createFromTimeString($endTimeRange);
        $stopLoop = true;
        $arraySchedule = [];

        do {
            $initialDay = Carbon::createFromTimeString($startTimeRange);
            $finalSchedule = $initialDay->addMinutes($this->transformTimeToMinutes($duration));

            if ($finalSchedule <= $finalDay) {
                $start = Carbon::createFromTimeString(
                        $finalSchedule->subMinutes($this->transformTimeToMinutes($duration))->toTimeString()
                    );

                $end = Carbon::createFromTimeString(
                        $finalSchedule->addMinutes($this->transformTimeToMinutes($duration))->toTimeString()
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
