<?php

    namespace App\Http\Controllers;

    use App\Models\Skill;
    use Illuminate\Http\Request;

    class SkillController extends Controller
    {
        public function index()
        {
            $skills = Skill::all();
            return response()->json($skills);
        }

        public function store(Request $request)
        {
            $request->validate([
                'skill_name' => 'required|string|max:255|unique:skills,skill_name',
            ]);

            try {
                $skill = Skill::create($request->all());
                return response()->json([
                    'message' => 'Habilidade criada com sucesso!',
                    'skill' => $skill
                ], 201);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao criar habilidade: ' . $e->getMessage()], 500);
            }
        }

        // show, update, destroy seriam implementados aqui
    }
    