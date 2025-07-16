    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('freelancer_skills', function (Blueprint $table) {
                $table->unsignedInteger('freelancer_id');
                $table->unsignedInteger('skill_id');
                $table->primary(['freelancer_id', 'skill_id']); // Chave primária composta

                // As chaves estrangeiras devem ser definidas APÓS as colunas serem criadas
                // E as tabelas referenciadas (freelancers, skills) devem existir antes desta migração rodar.
                // A ordem dos arquivos de migração (pelo timestamp) geralmente cuida disso.
            });
            // Adicione as chaves estrangeiras APÓS a criação da tabela
            Schema::table('freelancer_skills', function (Blueprint $table) {
                $table->foreign('freelancer_id')->references('freelancer_id')->on('freelancers')->onDelete('cascade');
                $table->foreign('skill_id')->references('skill_id')->on('skills')->onDelete('cascade');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('freelancer_skills');
        }
    };
    