<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Validators\TaskValidator;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;  // Import the correct BaseController

class TaskController extends BaseController
{
    /*public function index()
    {
        // Get all tasks (or add your actual query logic here)
        return response()->json(['message' => 'Fetching all tasks']);
    }*/
    public function index(Request $request)
    {
        $query = Task::query();

        // Filtering by status and due_date
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Search by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Pagination
        return response()->json($query->paginate(10));
    }



    /*public function show($id)
    {
        // Get a specific task by ID
        return response()->json(['message' => "Fetching task with ID: $id"]);
    }*/
    public function show($id)
    {
        $task = Task::find($id);
        return $task ? response()->json($task) : response()->json(['error' => 'Task not found'], 404);
    }



    /*public function store(Request $request)
    {
        $task = Task::create($request->all());
        return response()->json($task, 201);
    }*/
    public function store(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|unique:tasks', // Ensure title is unique
        'description' => 'string|nullable',
        'status' => 'in:pending,completed',
        'due_date' => 'required|date|after:today',
    ]);

    // If validation fails, return an error response
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Check for duplicate task before creating
    $existingTask = Task::where('title', $request->title)->first();

    if ($existingTask) {
        return response()->json(['error' => 'A task with this title already exists.'], 409); // 409 Conflict
    }

    // Create the new task
    try {
        $task = Task::create($request->all());
        return response()->json($task, 201); // Return a 201 response with the created task
    } catch (\Exception $e) {
        // Log the exception message
        \Log::error($e->getMessage());
        return response()->json(['error' => 'Unable to create task'], 500); // Return a 500 error if something goes wrong
    }
}


   
  /*public function update(Request $request, $id)
    {
        // Update an existing task
        return response()->json(['message' => "Task with ID: $id updated successfully"]);
    }*/
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|unique:tasks,title,' . $id,
            'description' => 'string|nullable',
            'status' => 'in:pending,completed',
            'due_date' => 'date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($request->all());
        return response()->json($task);
    }

   /*public function destroy($id)
    {
        // Delete a task
        return response()->json(['message' => "Task with ID: $id deleted successfully"]);
    }*/
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}







/*{
    // Get All Tasks with optional filtering, pagination, and search
    public function index(Request $request)
    {
        $query = Task::query();

        // Filtering by status and due_date
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        // Search by title
        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Pagination
        return response()->json($query->paginate(10));
    }

    // Get a specific Task
    public function show($id)
    {
        $task = Task::find($id);
        return $task ? response()->json($task) : response()->json(['error' => 'Task not found'], 404);
    }

    // Create a Task
    public function store(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|unique:tasks',
        'description' => 'string|nullable',
        'status' => 'in:pending,completed',
        'due_date' => 'required|date|after:today'
    ]);

    // If validation fails, return an error response
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Create the new task
    try {
        $task = Task::create($request->all());
        return response()->json($task, 201); // Return a 201 response with the created task
    } catch (\Exception $e) {
        // Log the exception message
        \Log::error($e->getMessage());
        return response()->json(['error' => 'Unable to create task'], 500); // Return a 500 error if something goes wrong
    }
}


    // Update a Task
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|unique:tasks,title,' . $id,
            'description' => 'string|nullable',
            'status' => 'in:pending,completed',
            'due_date' => 'date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($request->all());
        return response()->json($task);
    }

    // Delete a Task
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}*/
