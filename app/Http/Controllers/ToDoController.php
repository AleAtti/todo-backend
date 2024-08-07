<?php

namespace App\Http\Controllers;

use App\Models\ToDoModel;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class ToDoController extends Controller
{
    public function index(){
        // show all todos
        $todos = ToDoModel::all();
        return response()->json([
            'code' => 200,
            'message' => 'successful',
            'data' => $todos
        ], 200);
    }

    public function store(Request $request){
        try {

            $this-> validate(request(), [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

        } catch (ValidationException $exception) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
        }

        $data = request()->all();
        $todo = new ToDoModel();
        $todo->title = $data['title'];
        $todo->description = $data['description'];
        $todo->save();

        return response()->json([
            'code'=> 200,
            'message'=> 'successful',
            'data'=>$todo
        ], 200);
    }

    public function show($id)
    {
        // show only one todo
        $todo = ToDoModel::findOrFail($id);
        return response()->json([
            'code' => 200,
            'message' => 'successful',
            'data' => $todo
        ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);
        }catch (ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
        }

        $todo = ToDoModel::find($id);
        $todo->title = $validatedData['title'];
        $todo->description = $validatedData['description'];
        $todo->save();

        return response()->json([
            'code'=> 200,
            'message'=> 'successful',
            'data'=>$todo
        ], 200);
    }

    public function updateMarkToggle(Request $request, $id){

        try {
            $validatedData = $request->validate([
                'is_completed' => ['boolean', 'required']
            ]);
        }catch(ValidationException $exception){
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
        }

        $data = ToDoModel::findOrFail($id);
        if($data['is_completed']){
            $data['is_completed'] = 0;
            $data->save();
            return response()  ->json([
                'code' => 200,
                'message' => 'Todo not marked as completed',
                'data' => $data
            ], 200);
        }

        $data->is_completed = $validatedData['is_completed'];
        $data->save();
        return response()  ->json([
            'code' => 200,
            'message' => 'Todo marked as completed',
            'data' => $data
        ],200);

    }

    public function destroy($id)
    {
        $todo = ToDoModel::find($id);

        if ($todo->delete()) {
            return response()->json([
                'code' => 200,
                'message' => 'Todo deleted successfully',
                'data' =>$todo
            ], 200);
        } else {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to delete todo',
                'error' => 'no data found'
            ], 500);
        }
    }
}
