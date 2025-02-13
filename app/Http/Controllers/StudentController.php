<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Students retrieved successfully',
            'data' => StudentResource::collection($students),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:students',
            'email' => 'required|string|email|max:255|unique:students',
            'prodi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $student = Student::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'prodi' => $request->prodi,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student created successfully',
            'data' => new StudentResource($student),
        ], 201);
    }

    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Student retrieved successfully',
            'data' => new StudentResource($student),
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found',
                'data' => null,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:students,nim,' . $id,
            'email' => 'required|string|email|max:255|unique:students,email,' . $id,
            'prodi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $student->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'prodi' => $request->prodi,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student updated successfully',
            'data' => new StudentResource($student),
        ]);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found',
                'data' => null,
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Student deleted successfully',
            'data' => null,
        ]);
    }
}
