<?php

	 use Illuminate\Database\Migrations\Migration;
	 use Illuminate\Database\Schema\Blueprint;
	 use Illuminate\Support\Facades\Schema;


	 return new class () extends Migration {
    public function up(): void
    {
			 if (Schema::hasTable('slugs_translations')) {
					Schema::table('slugs_translations', function (Blueprint $table) {
						 $table->index(['slugs_id','lang_code']);

					});
			 }
			 if (Schema::hasTable('meta_boxes')) {
					Schema::table('meta_boxes', function (Blueprint $table) {
						 $table->index(['meta_key']);

					});
			 }
    }
			public function down()
			{
				 Schema::table('slugs_translations', function (Blueprint $table)
				 {
						$table->dropIndex(['slugs_id','lang_code']);
				 });
				 Schema::table('meta_boxes', function (Blueprint $table)
				 {
						$table->dropIndex(['meta_key']);
				 });
			}
};
