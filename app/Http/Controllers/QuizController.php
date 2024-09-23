<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return QuizResource::collection(Quiz::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'uuid' => ['required'],
            'user_id' => ['nullable', 'exists:users'],
            'type' => ['nullable'],
            'questions_amount' => ['required', 'integer'],
            'completed_at' => ['boolean'],
            'topics_id' => ['required', 'exists:topics'],
        ]);

        return new QuizResource(Quiz::create($data));
    }

    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'uuid' => ['required'],
            'user_id' => ['nullable', 'exists:users'],
            'type' => ['nullable'],
            'questions_amount' => ['required', 'integer'],
            'completed_at' => ['boolean'],
            'topics_id' => ['required', 'exists:topics'],
        ]);

        $quiz->update($data);

        return new QuizResource($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return response()->json();
    }
}
