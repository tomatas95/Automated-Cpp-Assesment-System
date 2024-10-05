<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use Database\Factories\ExerciseFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $user = User::factory()->create([
            'name' => 'John',
            'email' => 'johndoe@gmail.com'
        ]);

        Exercise::factory(5)->create([
            'user_id' => $user->id
        ]);

        // Exercise::create([
        //     'title' => 'Guess a Number',
        //     'content' => 'Lorem ipsum dolor amet va si dior wa ameratsu',
        //     'hint1' => 'Number is an integer',
        //     'hint2' => 'Number can be added',
        //     'hint3' => 'Numbers are close to each other',
        //     'difficulty' => 'normal',
        //     'time_required'=> '2 hours',
        //     'check1' => 6,
        //     'check1_answer' => 6,
        //     'check2' => 4,
        //     'check2_answer' => 4,
        //     'check3' => 10,
        //     'check3_answer' => 10,
        // ]);

        // Exercise::create([
        //     'title' => 'Fibonacci Number',
        //     'content' => 'In mathematics, the Fibonacci sequence is a sequence in which each number is the sum of the two preceding ones. Numbers that are part of the Fibonacci ...',
        //     'hint1' => 'xn-1 + xn-2 + xn-3...',
        //     'hint2' => 'x is just a variable',
        //     'hint3' => 'The sequence goes..',
        //     'difficulty' => 'easy',
        //     'time_required'=> '30 minutes',
        //     'check1' => 8,
        //     'check1_answer' => 8,
        //     'check2' => 21,
        //     'check2_answer' => 21,
        //     'check3' => 11,
        //     'check3_answer' => 11,
        // ]);
    }
}
