<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\Schedule;
use App\Models\Scheduling;
use App\Models\TypeScheduling;
use BadMethodCallException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SchedulingService
{
    public function get(int $id)
    {
        $scheduling = Scheduling::with('pet')
            ->with('schedule')
            ->with('typeScheduling')
            ->with('schedule.users')
            ->find($id);

        if(!$scheduling) {
            throw ValidationException::withMessages(['Agendamento não encontrado.']);
        }

        return $scheduling;
    }


    public function getAll()
    {
        $schedulings = Scheduling::with('pet')
            ->with('schedule')
            ->with('typeScheduling')
            ->with('schedule.users')
            ->whereRelation('pet', 'user_id', Auth::user()->id)
            ->get();

        if(!$schedulings->toArray()) {
            throw ValidationException::withMessages(['Não há agendas salvas.']);
        }

        return $schedulings;
    }


    public function getFutureSchedulings()
    {
        $schedulings = Scheduling::with('pet')
            ->with('schedule')
            ->with('typeScheduling')
            ->with('schedule.users')
            ->whereRelation('pet', 'user_id', Auth::user()->id)
            ->whereRelation('schedule', 'date', '>=', Carbon::today('America/Sao_Paulo')->format('Y-m-d'))
            ->get()
            ->filter(function ($scheduling) {
                if ($scheduling->schedule->date == Carbon::today('America/Sao_Paulo')->format('Y-m-d')) {
                    return $scheduling->schedule->start_time->format('H:i') >= Carbon::now('America/Sao_Paulo')->format('H:i');
                }

                return true;
            });

        if(!$schedulings->toArray()) {
            throw ValidationException::withMessages(['Não há agendas salvas.']);
        }

        return $schedulings;
    }


    public function store(array $schedulingData)
    {
        [ , $schedule, ] = $this->verifyRelations($schedulingData);

        try  {
            DB::beginTransaction();
                $scheduling = Scheduling::create($schedulingData);
                $scheduling->load('pet');
                $scheduling->load('ong');
                $scheduling->load('schedule');
                $scheduling->load('typeScheduling');
                $scheduling->load('schedule.users');

                $schedule->available = false;
                $schedule->save();
            DB::commit();

            return response()->json(['data' => ['scheduling' => $scheduling]], 201);
        }
        catch (BadMethodCallException | ValidationException $e)
        {
            DB::rollBack();
            throw ValidationException::withMessages([$e->getMessage()]);
        }
    }


    public function update(int $id, array $schedulingData)
    {
        $scheduling = Scheduling::find($id);

        if(!!!$scheduling) {
            return response()->json(['error' => 'Agendamento não encontrado.'], 404);
        }

        try  {
            DB::beginTransaction();
                $scheduling->description = $schedulingData['description'];
                $scheduling->schedule->available = true;
                $scheduling->schedule_id = $schedulingData['schedule_id'];
                $scheduling->push();
                $scheduling->save();

                $newScheduling = Scheduling::find($id);
                $newScheduling->schedule->available = false;
                $newScheduling->push();
                $newScheduling->save();
            DB::commit();

            return response()->json(['data' => ['status' => 'Atualizado com sucesso!']], 200);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    public function delete(int $id)
    {
        $scheduling = Scheduling::find($id);

        if(!$scheduling) {
            throw ValidationException::withMessages(['Agendamento não encontrado.']);
        }

        DB::beginTransaction();
                $scheduling->schedule->available = true;
                $scheduling->push();
                $scheduling->save();
                $scheduling->delete();
            DB::commit();

        return response()->json(['data' => ['status' => 'Excluído com sucesso!']], 200);
    }


    private function verifyRelations(array $schedulingData)
    {
        $pet = Pet::find($schedulingData['pet_id']);
        if (!$pet) {
            throw ValidationException::withMessages(['O animal informado não foi encontrado.']);
        }

        $schedule = Schedule::find($schedulingData['schedule_id']);
        if (!$schedule) {
            throw ValidationException::withMessages(['A agenda informada não foi encontrada.']);
        } else if (!$schedule->available) {
            throw ValidationException::withMessages(['A agenda informada não está mais disponível.']);
        }

        $typeScheduling = TypeScheduling::find($schedulingData['type_scheduling_id']);
        if (!$typeScheduling) {
            throw ValidationException::withMessages(['O tipo de agendamento informado não foi encontrado.']);
        }

        return [ $pet, $schedule, $typeScheduling ];
    }
}
