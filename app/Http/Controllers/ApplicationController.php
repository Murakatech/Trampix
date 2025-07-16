<?php

    namespace App\Http\Controllers;

    use App\Models\Application;
    use Illuminate\Http\Request;

    class ApplicationController extends Controller
    {
        public function index()
        {
            $applications = Application::with(['freelancer.user', 'jobVacancy.company.user'])->get(); // Carrega relacionamentos
            return response()->json($applications);
        }

        public function store(Request $request)
        {
            $request->validate([
                'freelancer_id' => 'required|exists:freelancers,freelancer_id',
                'vacancy_id' => 'required|exists:job_vacancies,vacancy_id',
                'application_status' => 'in:Pending,Reviewed,Interview,Hired,Rejected',
            ]);

            try {
                // Verifica se já existe uma candidatura única
                $existingApplication = Application::where('freelancer_id', $request->freelancer_id)
                                                ->where('vacancy_id', $request->vacancy_id)
                                                ->first();
                if ($existingApplication) {
                    return response()->json(['message' => 'Candidatura já existe para este freelancer e vaga.'], 409); // Conflict
                }

                $application = Application::create($request->all());
                return response()->json([
                    'message' => 'Candidatura criada com sucesso!',
                    'application' => $application
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao criar candidatura: ' . $e->getMessage()], 500);
            }
        }

        // show, update, destroy seriam implementados aqui
    }
    