<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\TasksReorderUpdateRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Phase;
use App\Models\Task;

class TaskController extends Controller
{

    public function kanban()
    {
        return view('tasks.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Phase::with(['tasks.user', 'tasks.labels'])
            ->withCount('tasks')
            ->get();
    }

    /**
     * Display a listing of the Users resource.
     */
    public function users()
    {
        return \App\Models\User::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Create a new task from the $request
        Task::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if ($request->phase_id  && $request->phase_id != $task->phase_id || $task->phase_id != 4) {
            $request->merge(['completed_at' => $request->updated_at]);
        }
        // Update the task with the new data
        $task->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function boardsDestroy(Task $task)
    {
        Phase::destroy($task->id);
    }

    public function reorder(TasksReorderUpdateRequest $request)
    {

        $data = collect($request->phases)
            // ->recursive() // make all nested arrays a collection
            ->map(function ($phase) {
                return collect($phase['tasks'])->map(function ($task) use ($phase) {
                    return ['id' => $task['id'], 'position' => $task['position'], 'phase_id' => $phase['id']];
                });
            })
            ->flatten(1)
            ->toArray();


        $recordsToUpdate = [];

        foreach ($data as $record) {
            // Check if the record with the same primary key already exists in the database
            $existingRecord = Task::find($record['id']);

            if ($existingRecord) {
                // Record exists, so add it to the recordsToUpdate array
                $recordsToUpdate[] = $record;
            } else {
                // Record doesn't exist, so add it to the recordsToInsert array
                $recordsToInsert[] = $record;
            }
        }

        // Update existing records
        foreach ($recordsToUpdate as $record) {
            Task::where('id', $record['id'])->update($record);
        }
    }
}
