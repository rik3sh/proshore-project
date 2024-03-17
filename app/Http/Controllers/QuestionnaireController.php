<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\QuestionnaireService;
use App\Http\Resources\QuestionnaireResource;
use App\Http\Requests\StoreQuestionnaireRequest;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of all active questionnaires in descending order of creation.
     *
     * @return \Inertia\Response - Returns view with list of all active questionnaires.
    */
    public function index(): Response 
    {
        $questionnaires = Questionnaire::active()->latest()->with('questions.options')->get();

        return Inertia::render('Questionnaire/Index', [
            'questionnaires' => $questionnaires
        ]);
    }

    /**
     * Show the form for creating a new questionnaire.
     *
     * @return \Inertia\Response - Returns Create Questionnaire view.
    */
    public function create(): Response 
    {
        return Inertia::render('Questionnaire/Create');
    }

    /**
     * Store a newly created questionnaire in storage.
     *
     * @param  \App\Http\Requests\StoreQuestionnaireRequest  $request - Request instance with validated request data.
     * @param  \App\Services\QuestionnaireService  $questionnaireService - The service class instance to handle business logic.
     * @return RedirectResponse - Redirects back to questionnaires index page with success message.
    */
    public function store(StoreQuestionnaireRequest $request, QuestionnaireService $questionnaireService): RedirectResponse  
    {
        // Get 5 random Physics questions
        $physicsQuestions = $questionnaireService->fetchRandomQuestions('physics');

        // Get 5 random Chemistry questions
        $chemistryQuestions = $questionnaireService->fetchRandomQuestions('chemistry');

        $randomChemistryAndPhysicsQuestionsIds = DB::query()
            ->fromSub($chemistryQuestions->union($physicsQuestions), 'sub')
            ->pluck('id')
            ->toArray();

        // Store the new Questionnaire
        $questionnaireService->storeQuestionnaireAndRelatedQuestions($request->only('title', 'expiry_date'), $randomChemistryAndPhysicsQuestionsIds);

        return redirect(route('questionnaires.index'))->with('success', 'Questionnaire created successfully.');
    }

    /**
     * Display the specific questionnaire along with its questions and their options.
     *
     * @param  int  $qid - Id of the questionnaire to be displayed.
     * @return RedirectResponse - Returns view with the specific questionnaire.
    */
    public function show_questions($qid): View 
    {
        $questionnaire = Questionnaire::with('questions.options')->findOrFail($qid);

        return view('questionnaires.show-questions', compact('questionnaire'));
    }

    /**
     * Send the specified questionnaire to all students via email.
     *
     * @param  \App\Services\QuestionnaireService  $questionnaireService - The service class instance to handle business logic.
     * @param  int  $qid - Id of the questionnaire to be sent to students.
     * @return RedirectResponse - Redirects back to questionnaires index page with success message.
    */
    public function send_to_students(QuestionnaireService $questionnaireService, int $qid): RedirectResponse 
    {
        $questionnaireService->storeAndSendMailToStudents($qid);

        return redirect(route('questionnaires.index'))->with('success', 'Questionnaire sent to students successfully.');
    }
}
