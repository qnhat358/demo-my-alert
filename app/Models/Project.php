<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    public function getAll(){
        return DB::select('SELECT projects.id AS Project_id, projects.project_name, accounts.account_name, accounts.id AS Account_id  FROM projects INNER JOIN accounts ON projects.account_id = accounts.id ORDER BY projects.id');
    }

    public function add($projectName, $accountId){
        return DB::select('INSERT into projects (project_name, account_id, created_at) values (?, ?, ?)', [$projectName, $accountId, date ('Y-m-d H:i:s')
    ]);
    }

    public function edit($projectName, $accountId, $projectId)
    {
        return DB::update('UPDATE projects SET project_name = ?, account_id= ? WHERE id = ?',[$projectName, $accountId, $projectId]);
    }
}
