<?php

    namespace App\Http\Controllers;

    use App\Models\Company;
    use App\Models\User; // Necessário para criar o usuário antes da empresa
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;

    class CompanyController extends Controller
    {
        public function index()
        {
            $companies = Company::with('user')->get(); // Carrega o usuário relacionado
            return response()->json($companies);
        }

        public function store(Request $request)
        {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'company_name' => 'required|string|max:255',
                'cnpj' => 'required|string|max:18|unique:companies,cnpj',
                // Adicione validação para outros campos
            ]);

            try {
                // 1. Criar o usuário na tabela Users
                $user = User::create([
                    'email' => $request->email,
                    'password_hash' => Hash::make($request->password),
                    'user_type' => 'Empresa',
                    'status' => 'Pending Approval',
                ]);

                // 2. Criar o perfil da empresa usando o user_id recém-criado
                $company = Company::create([
                    'user_id' => $user->user_id, // Usa o ID do usuário criado
                    'company_name' => $request->company_name,
                    'trade_name' => $request->trade_name,
                    'sector' => $request->sector,
                    'description' => $request->description,
                    'cnpj' => $request->cnpj,
                    'logo_url' => $request->logo_url,
                    'location' => $request->location,
                ]);

                return response()->json([
                    'message' => 'Empresa criada com sucesso!',
                    'company' => $company->load('user')
                ], 201);

            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao criar empresa: ' . $e->getMessage()], 500);
            }
        }

        // show, update, destroy seriam implementados aqui
    }
    