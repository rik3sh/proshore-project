<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\DB;
use App\Services\QuestionnaireService;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Resources\QuestionnaireResource;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;


class QuestionnaireTest extends TestCase
{
    use RefreshDatabase;

    public function test_5_same_subjects_are_being_created()
    {
        Question::factory()->count(20)->with_options()->create();
        
        $questionnaireService = new QuestionnaireService();
    
        $query = $questionnaireService->fetchRandomQuestions('physics');
        $response = $query->get();

        $this->assertCount(5, $response);

        $response->each(function ($question) {
            $getQuestionDetails = Question::find($question->id);
            $this->assertEquals('physics', $getQuestionDetails->subject);
        });
    }

    public function test_questionnaire_and_relation_being_created()
    {
        Question::factory()->count(20)->with_options()->create();
        
        $questionnaireService = new QuestionnaireService();

        $subjects = ['physics', 'chemistry'];
        $randomChemistryAndPhysicsQuestionsIds = [];

        foreach ($subjects as $subject) {
            $query = $questionnaireService->fetchRandomQuestions($subject);
            $randomChemistryAndPhysicsQuestionsIds = array_merge($randomChemistryAndPhysicsQuestionsIds, $query->pluck('id')->toArray());
            $response = $query->get();
    
            $this->assertCount(5, $response);
    
            $response->each(function ($question) use($subject) {
                $getQuestionDetails = Question::find($question->id);
                $this->assertEquals($subject, $getQuestionDetails->subject);
            });
        }

        info($randomChemistryAndPhysicsQuestionsIds);

        $dataToSave = [
            'title' => fake()->sentence,
            'expiry_date' => fake()->date
        ];

        $questionnaire = $questionnaireService->storeQuestionnaireAndRelatedQuestions($dataToSave, $randomChemistryAndPhysicsQuestionsIds);
        
        $this->assertModelExists($questionnaire);
        $this->assertDatabaseCount('question_questionnaire', 10);
    }
}
