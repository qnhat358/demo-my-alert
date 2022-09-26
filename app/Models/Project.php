<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    public function getAll(){
        return DB::select('SELECT * FROM projects');
    }

    public function add($projectName, $accountId){
        return DB::select('INSERT into projects (project_name, account_id, created_at) values (?, ?, ?)', [$projectName, $accountId, date ('Y-m-d H:i:s')
    ]);
    }

}
