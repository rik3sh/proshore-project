<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Questionnaire;
use App\Mail\QuestionnaireSent;
use App\Models\QuestionnaireUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class QuestionnaireService
{
    /**
     * Fetch a random set of questions for a specific subject.
     *
     * @param string $subject - The subject to fetch the questions from.
     * @param int    $limit - Limit the number of questions fetched. Default is 5.
     * 
     * @return \Illuminate\Support\Collection - Collection of the IDs of the random questions.
    */
    public function fetchRandomQuestions(string $subject, int $limit = 5)
    {
        return DB::table('questions')
            ->where('subject', $subject)
            ->inRandomOrder()
            ->select('id')  
            ->limit($limit);
    }

    /**
     * Create a new questionnaire and attach it with a set of questions.
     *
     * @param array $data - The data to create the questionnaire with.
     * @param array $questionIds - The IDs of the questions to be attached to the created questionnaire.
     * 
     * @return Questionnaire - The created Questionnaire instance.
    */
    public function storeQuestionnaireAndRelatedQuestions(array $data, array $questionIds)
    {
        // Create a new Questionnaire
        $questionnaire = Questionnaire::create($data);

        // Attach the random questions to this new Questionnaire
        $questionnaire->questions()->attach($questionIds);

        return $questionnaire;
    }

    /**
     * Stores user details into questionnaire, send mail to each student with exam code.
     *
     * @param int $questionnaireId - The id of the questionnaire
     * 
     * @return Questionnaire - Returns the Questionnaire instance.
    */
    public function storeAndSendMailToStudents(int $questionnaireId) 
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);

        $students = User::student()->get();
        
        foreach ( $students as $student ) {
            $code = Str::uuid();

            $questionnaire->users()->attach([$student->id => ['exam_code' => $code, 'status' => QuestionnaireUser::STATUS_PENDING, 'created_at' => now()]]);

            Mail::to($student)->queue(new QuestionnaireSent($student->name, $code));
        }

        return $questionnaire;
    }
}