<?php
    
    namespace App\Http\Controllers;

    use App\Models\Freelancer;
    use App\Models\User; // Necessário para criar o usuário antes do freelancer
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash; // Para hash de senha

    class FreelancerController extends Controller
    {
        public function index()
        {
            $freelancers = Freelancer::with('user')->get(); // Carrega o usuário relacionado
            return response()->json($freelancers);
        }

        public function store(Request $request)
        {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'full_name' => 'required|string|max:255',
                'area_of_expertise' => 'nullable|string|max:255',
                'biography' => 'nullable|string',
                // Adicione validação para outros campos conforme necessário
            ]);

            try {
                // 1. Criar o usuário na tabela Users
                $user = User::create([
                    'email' => $request->email,
                    'password_hash' => Hash::make($request->password),
                    'user_type' => 'Freelancer',
                    'status' => 'Pending Approval',
                ]);

                // 2. Criar o perfil de freelancer usando o user_id recém-criado
                $freelancer = Freelancer::create([
                    'user_id' => $user->user_id, // Usa o ID do usuário criado
                    'full_name' => $request->full_name,
                    'area_of_expertise' => $request->area_of_expertise,
                    'biography' => $request->biography,
                    // Preencha outros campos aqui
                ]);

                return response()->json([
                    'message' => 'Freelancer criado com sucesso!',
                    'freelancer' => $freelancer->load('user') // Carrega o usuário para o retorno
                ], 201);

            } catch (\Exception $e) {
                // Se o usuário foi criado mas o freelancer falhou, você pode querer reverter o usuário
                return response()->json(['message' => 'Erro ao criar freelancer: ' . $e->getMessage()], 500);
            }
        }

        // show, update, destroy seriam implementados aqui
    }
    