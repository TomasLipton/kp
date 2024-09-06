<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicsResource;
use App\Models\Topics;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function index()
    {
        return TopicsResource::collection(Topics::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'title' => ['required'],
            'description' => ['required'],
            'picture' => ['required'],
            'parent_id' => ['required'],
        ]);

        return new TopicsResource(Topics::create($data));
    }

    public function show(Topics $topics)
    {
        return new TopicsResource($topics);
    }

    public function update(Request $request, Topics $topics)
    {
        $data = $request->validate([
            'name' => ['required'],
            'title' => ['required'],
            'description' => ['required'],
            'picture' => ['required'],
            'parent_id' => ['required'],
        ]);

        $topics->update($data);

        return new TopicsResource($topics);
    }

    public function destroy(Topics $topics)
    {
        $topics->delete();

        return response()->json();
    }
}
