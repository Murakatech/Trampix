<?php

    namespace App\Http\Controllers;

    use App\Models\Log;
    use Illuminate\Http\Request;

    class LogController extends Controller
    {
        public function index()
        {
            $logs = Log::all();
            return response()->json($logs);
        }

        public function store(Request $request)
        {
            $request->validate([
                'user_id' => 'nullable|exists:users,user_id',
                'action' => 'required|string|max:255',
                'entity_type' => 'nullable|string|max:255',
                'entity_id' => 'nullable|integer',
                'details' => 'nullable|string',
            ]);

            try {
                $log = Log::create($request->all());
                return response()->json([
                    'message' => 'Log registrado com sucesso!',
                    'log' => $log
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao registrar log: ' . $e->getMessage()], 500);
            }
        }

        // show, update, destroy seriam implementados aqui
    }
    