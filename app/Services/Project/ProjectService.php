<?php

namespace App\Services\Project;

use App\Models\Project;
use App\Models\Account;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectService implements ProjectServiceInterface
{
    private $projects;

    public function __construct(Project $Project)
    {
        $this->projects = $Project;
    }

    public function getAll()
    {
        $projectList = $this->projects->getAll();
        
        return response()->json([
            'message' => 'Get all successful',
            'status' => 'success',
            'data' => $projectList,
        ], 200);
    }

    public function get($id)
    {
        try {
            $project = Project::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Project not found',
                'status' => 'error',
            ], 404);
        }
        $projectList = $this->projects->getById($id);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Get project successful',
                'status' => 'success',
                'project' => $projectList[0],
            ], 200);
        } else $response = response()->json([
            'message' => 'Get project failed',
            'status' => 'error',
        ], 404);
        return $response;
    }

    public function create($request)
    {
        // dd('aaaaa');
        try {
            $account = Account::findOrFail($request->account_id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Account not found',
                'status' => 'error',
            ], 404);
        }
        // dd($request->collect());
        $projectList = $this->projects->add($request->project_name, $request->account_id);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Add project successful',
                'status' => 'success',
                'project' => $request->all()
            ], 201);
        } else $response = response()->json([
            'message' => 'Add project failed',
            'status' => 'error',
        ], 404);
        return $response;
    }

    public function update($request, $id)
    {
        try {
            $account = Account::findOrFail($request->account_id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Account not found',
                'status' => 'error',
            ], 404);
        }

        try {
            $project = Project::findOrFail($id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Project not found',
                'status' => 'error',
            ], 404);
        }

        $projectList = $this->projects->edit($request->project_name, $request->account_id, $id);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Edit successful',
                'status' => 'success',
                'project' => [
                    'project_name' => $request->project_name,
                    'account_id' => $request->account_id,
                    'project_id' => $id,
                ]
            ], 200);
        } else $response = response()->json([
            'message' => 'Edit failed',
            'status' => 'error',
        ], 404);
        return $response;
    }

    public function delete($id)
    {
        try {
            $project = Project::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Project not found',
                'status' => 'error',
            ], 404);
        }
        $projectList = $this->projects->deleteById($id);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Delete successful',
                'status' => 'success',
            ], 200);
        } else $response = response()->json([
            'message' => 'Delete failed',
            'status' => 'error',
        ], 404);
        return $response;
    }
}
