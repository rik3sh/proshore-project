<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\ExamService;
use Illuminate\Http\RedirectResponse;

class ExamController extends Controller
{
    /**
     * Start the exam for a student after he/she clicks the link from their email and mark as ongoing
     *
     * @param  string $code - The request instance containing all the request data.
     * @return RedirectResponse - Redirects back to index page with success message after operation.
    */
    public function start_exam(string $code, ExamService $examService)
    {
        $questionnaireUser = $examService->getQuestionnaireUser($code);

        if(!$examService->canTakeExam($questionnaireUser, auth()->id())) {
            return redirect('/')
                ->with('message', 'You cannot enter someone else\'s exam.');
        }

        if($examService->isExamCompleted($questionnaireUser)) {
            return redirect('/')
                ->with('message', 'You have already completed this exam.');
        }

        $examService->markExamAsOngoing($questionnaireUser);
        
        return Inertia::render('Exam/Index', ['questionnaireUser' => $questionnaireUser]);
    }

    /**
     * Stores the provided option of a question that a user has answered in an exam and marks the exam as completed.
     *
     * @param  Request $request - The request instance containing all the request data.
     * @return RedirectResponse - Redirects back to index page with success message after operation.
    */
    public function store(Request $request, ExamService $examService): RedirectResponse 
    {
        $examService->finishExam($request);

        return redirect('/')
            ->with('message', 'Exam completed!');
    }
}
    