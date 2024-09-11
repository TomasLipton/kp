<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionAnswerResource;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;

class QuestionAnswerController extends Controller
{
    public function index()
    {
        return QuestionAnswerResource::collection(QuestionAnswer::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question_id' => ['required', 'exists:questions'],
            'text' => ['required'],
            'picture' => ['required'],
            'order' => ['nullable', 'integer'],
        ]);

        return new QuestionAnswerResource(QuestionAnswer::create($data));
    }

    public function show(QuestionAnswer $questionAnswer)
    {
        return new QuestionAnswerResource($questionAnswer);
    }

    public function update(Request $request, QuestionAnswer $questionAnswer)
    {
        $data = $request->validate([
            'question_id' => ['required', 'exists:questions'],
            'text' => ['required'],
            'picture' => ['required'],
            'order' => ['nullable', 'integer'],
        ]);

        $questionAnswer->update($data);

        return new QuestionAnswerResource($questionAnswer);
    }

    public function destroy(QuestionAnswer $questionAnswer)
    {
        $questionAnswer->delete();

        return response()->json();
    }
}
