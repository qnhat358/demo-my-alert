<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    public function getAll(){
        return DB::select('SELECT projects.id AS project_id, projects.project_name, accounts.account_name, accounts.id AS account_id  FROM projects INNER JOIN accounts ON projects.account_id = accounts.id ORDER BY projects.id');
    }

    public function getById($projectId){
        return DB::select('SELECT projects.id AS project_id, projects.project_name, accounts.account_name, accounts.id AS account_id  FROM projects INNER JOIN accounts ON projects.account_id = accounts.id WHERE projects.id = ? ORDER BY projects.id',[$projectId]);
    }

    public function add($projectName, $accountId){
        return DB::insert('INSERT into projects (project_name, account_id, created_at) values (?, ?, ?)', [$projectName, $accountId, date ('Y-m-d H:i:s')]);
    }

    public function edit($projectName, $accountId, $projectId)
    {
        return DB::update('UPDATE projects SET project_name = ?, account_id= ? WHERE id = ?',[$projectName, $accountId, $projectId]);
    }

    public function deleteById($projectId)
    {
        return DB::delete('DELETE FROM projects WHERE id = ?',[$projectId]);
    }
}
