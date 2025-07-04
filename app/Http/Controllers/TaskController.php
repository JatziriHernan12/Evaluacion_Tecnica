<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Person;

class TaskController extends Controller
{
    //
    public function index()
    {
        return Task::with('people')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        $task->update($validated);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }

    public function assign_task(Request $request, $task_id)
    {
        $task = Task::findOrFail($task_id);
        $person_id = $request->input('person_id');
        $task->people()->attach($person_id);

        return response()->json(['message' => 'Persona asignada']);
    }

    public function unassign_task(Request $request, $task_id)
    {
        $task = Task::findOrFail($task_id);
        $person_id = $request->input('person_id');
        $task->people()->detach($person_id);

        return response()->json(['message' => 'Persona desasignada']);
    }
}
