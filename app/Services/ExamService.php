<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\QuestionnaireUser;

class ExamService 
{
    /**
     * Fetches QuestionnaireUser instance with related data based on provided exam code.
     *
     * @param  string $code - Provided exam code.
     * @return QuestionnaireUser - Fetched QuestionnaireUser instance.
    */
    public function getQuestionnaireUser(string $code): QuestionnaireUser
    {
        return QuestionnaireUser::where('exam_code', $code)
            ->with('questionnaire.questions.options:id,answer,question_id', 'user')
            ->firstOrFail();
    }

    /**
     * Checks if provided user can take exam.
     *
     * @param  QuestionnaireUser $questionnaireUser - Provided QuestionnaireUser instance.
     * @param  int $userId - Id of authenticated user.
     * @return bool - Whether user can take exam or not.
    */
    public function canTakeExam(QuestionnaireUser $questionnaireUser, int $userId): bool
    {
        return $questionnaireUser->user_id == $userId;
    }

    /**
     * Checks if provided questionnaire user has already completed the exam.
     *
     * @param  QuestionnaireUser $questionnaireUser - Provided QuestionnaireUser instance.
     * @return bool - Whether exam has been completed or not.
    */
    public function isExamCompleted(QuestionnaireUser $questionnaireUser): bool
    {
        return $questionnaireUser->status == QuestionnaireUser::STATUS_COMPLETED;
    }
    
    /**
     * Checks if exam has expired.
     *
     * @param  QuestionnaireUser $questionnaireUser - Provided QuestionnaireUser instance.
     * @return bool - Whether exam has been completed or not.
    */
    public function isExpired(QuestionnaireUser $questionnaireUser): bool
    {
        return $questionnaireUser->questionnaire->expiry_date <= date('Y-m-d');
    }

    /**
     * Updates status of provided questionnaire user to ONGOING and saves it.
     *
     * @param  QuestionnaireUser $questionnaireUser - Provided QuestionnaireUser instance.
    */
    public function markExamAsOngoing(QuestionnaireUser $questionnaireUser): void
    {
        $questionnaireUser->status = QuestionnaireUser::STATUS_ONGOING;
        $questionnaireUser->save();
    }

    /**
     * Updates status of exam to finished and store the answers.
     *
     * @param  Request $request - The request instance containing all the request data.
     * @return QuestionnaireUser $questionnaireUser.
    */
    public function finishExam(Request $request): QuestionnaireUser
    {
        // Find the questionnaire user by the provided exam code.
        $questionnaireUser = QuestionnaireUser::where('exam_code', $request->code)->firstOrFail();
        
        // Attach the chosen option to the questionnaire user.
        $questionnaireUser->options()->attach($request->option_id);

        // Set the status of the questionnaire user to 'completed'.
        $questionnaireUser->status = QuestionnaireUser::STATUS_COMPLETED;

        // Save the changes made to the questionnaire user.
        $questionnaireUser->save();

        return $questionnaireUser;
    }
}