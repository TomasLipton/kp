<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return QuestionResource::collection(Question::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question_pl' => ['required'],
            'question_ru' => ['nullable'],
            'question_type' => ['required'],
            'picture' => ['nullable'],
            'explanation_pl' => ['nullable'],
            'explanation_ru' => ['nullable'],
            'topics_id' => ['required', 'exists:topics'],
        ]);

        return new QuestionResource(Question::create($data));
    }

    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'question_pl' => ['required'],
            'question_ru' => ['nullable'],
            'question_type' => ['required'],
            'picture' => ['nullable'],
            'explanation_pl' => ['nullable'],
            'explanation_ru' => ['nullable'],
            'topics_id' => ['required', 'exists:topics'],
        ]);

        $question->update($data);

        return new QuestionResource($question);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json();
    }
}
