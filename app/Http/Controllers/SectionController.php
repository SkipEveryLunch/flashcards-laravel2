<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\User;
use App\Models\Question;
use App\Models\Learning;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

function nextSpan($span){
    $spanArr = [1,7,14,28,56];
    $result = array_search($span, $spanArr);
    if($result === count($spanArr)-1){
        return 56;
    }else{
        return $spanArr[$result+1];
    }
}

class SectionController extends Controller
{

    public function index(){
        $sections = Section::all();
        return $sections;
    }
    public function show($id)
    {
        $section = Section::with(["questions"])->find($id);
        return $section;
    }
    public function store(Request $req)
    {
        $section = Section::create([
            'id'=>$req->input('id'),
            'name'=>$req->input('name'),
        ]);
        return $section;
    }
    public function update(Request $req,$id)
    {
        $section = Section::find($id);
        $section->update(
            $req->only("name")
        );
        return $section;
    }
    public function destroy($id){
        Section::destroy($id);
        return response(null,Response::HTTP_NO_CONTENT);
    }
    public function newQuestions(Request $req,$id)
    {
        $howManyQ = env('HOW_MANY_QUESTIONS');
        $user = $req->user();
        if($user->next_assignment>date("Y-m-d")){
            return response()->json(["message"=>"next assignment isn't yet"]);
        }else{
            $questions = Question::inRandomOrder()->whereDoesntHave('users', function($q)use($user){
                $q->where('user_id', '=', $user->id);
            })->where("section_id","=",$id)->take($howManyQ)->get();
            return $questions;
        }
    }
    public function answerQuestions(Request $req)
    {
        $user = $req->user();
        $qIds = $req->input('question_ids');
        $result = [];
        foreach($qIds as $qId){
            $learning = Learning::create([
                "user_id"=>$user->id,
                "question_id"=>$qId,
                "next_period"=>date('Y-m-d', strtotime('+1 day')),
                "next_span"=>1
            ]);
            array_push($result, $learning);
        }
        $user->update([
            "next_assignment"=>date('Y-m-d', strtotime('+1 day'))
        ]);
        return $result;
    }
    public function reviewQuestions(Request $req,$id)
    {
        $user = $req->user();
        $questions = $user->questions->where("section_id","=",$id)->filter(function($q){
            return $q->pivot->next_period <= date("Y-m-d");
        })->values();
        return $questions;
    }
    public function answerReviews(Request $req){
        $qIds = $req->input('question_ids');
        $user = $req->user();
        $result = [];
        foreach($qIds as $qId){
            $learning = Learning::where("user_id",$user->id)->where("question_id",$qId)->first();
            $learning->update([
                "next_period"=>date("Y-m-d",strtotime($learning->next_period."+" . $learning->next_span . " day")),
                "next_span"=>nextSpan($learning->next_span)
            ]);
            array_push($result, $learning);
        }
        return $result;
    }
}
