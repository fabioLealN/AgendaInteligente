<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\Schedule;
use App\Models\Scheduling;
use App\Models\TypeScheduling;
use App\Models\User;
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
        $user = User::find(Auth::user()->id);
        return $user->schedulings()->with(["typeScheduling", "schedule.users"])->paginate();
    }


    public function getFutureSchedulings()
    {
        $user = User::find(Auth::user()->id);

        return $user->schedulings()->whereRelation("schedule", 'date', '>=', date('Y-m-d'))->with(["typeScheduling", "schedule" => function($query) {
            $query->orderBy('date', 'asc')->orderBy("start_time", "asc");
        },"schedule.speciality", "pet", "pet.breed", "pet.size"])->limit(5)->get();
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

                $schedule->available = false;
                $schedule->save();

            DB::commit();

            return response()->json(['data' => $scheduling], 201);
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
                $scheduling->ong_id = $schedulingData['ong_id'];
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
