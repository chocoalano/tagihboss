<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserroleView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE OR REPLACE VIEW userrole_view 
            AS SELECT users.id,
                         users.user_id,
                         users.location,
                         users.nik,
                         users.name,
                         users.email,
                         users.password,
                         users.gender,
                         users.whatsup_number,
                         users.agree_wa_notification,
                         users.last_active,
                         roles.name AS rolename 
                         FROM users JOIN  
                         model_has_roles ON model_has_roles.model_id=users.id
                        JOIN roles ON roles.id=model_has_roles.role_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userrole_view');
    }
}
