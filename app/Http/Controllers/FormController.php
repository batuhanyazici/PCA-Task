<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class FormController extends Controller
{
    public function submit(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:10',
                'email' => 'required|email|max:250',
                'message' => 'required|string',
            ]);

            return response()->json(['message' => 'Form successfully submitted!']);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed!',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
