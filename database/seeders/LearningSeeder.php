<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\User;
use App\Models\Learning;

function rand_els($arr){
    $response = [] ;
    $randNum = random_int(1,count($arr));
    if($randNum == 1){
        return [$arr[random_int(1,count($arr))]];
    }
    foreach( array_rand($arr,2) as $key) {
        $response[] = $arr[$key];
    }
    return $response;
};

class LearningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $qIds = Question::all()->pluck("id")->toArray(); 
        User::all()->each(
            function(User $user)use($qIds){
                $qRandIds = rand_els($qIds);
                foreach($qRandIds as $qRandId){
                    Learning::factory()->create([
                        "user_id" => $user->id,
                        "question_id" => $qRandId,
                    ]);
                }
            }
        );
    }
}
