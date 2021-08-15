<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    public function index(){
        $questions = Question::all();
        return response()->json($questions);
    }
    public function show(Request $req, $id)
    {
        $question = Question::find($id);
        return response()->json($question);
    }
    public function store(Request $req)
    {
        $question = Question::create(
            $req->only("front","back")
        );
        return response()->json($question,Response::HTTP_CREATED);
    }
    public function update(Request $req,$id){
        $question = Question::find($id);
        $question->update(
            $req->only("front","back")
        );
        return response()->json($question,Response::HTTP_ACCEPTED);
    }
    public function destroy($id){
        Question::destroy($id);
        return response(null,Response::HTTP_NO_CONTENT);
    }
}