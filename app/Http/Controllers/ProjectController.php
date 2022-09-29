<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Account;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $projects;

    public function __construct(Project $Project)
    {
        $this->projects = $Project;
    }

    public function index()
    {
        $projectList = $this->projects->getAll();
        return $projectList;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //code...
            $request->validate([
                'projectName' => 'required|min:5',
                'accountId' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message'    => 'Error',
                'status' => 'Failed',
                'errors' => $e->errors(),
            ], 404);
        }
        try {
            //code...
            $account = Account::findOrFail($request->accountId);
            // return($account);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Account not found',
                'status' => 'Failed',
            ], 404);
            //throw $th;
        }
        $projectList = $this->projects->add($request->projectName, $request->accountId);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Add project successful',
                'status' => 'Success',
            ], 201);
        } else $response = response()->json([
            'message' => 'Add project failed',
            'status' => 'Failed',
        ], 404);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $Project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $Project
     * @return \Illuminate\Http\Response
     */
    // public function edit(Request $request)
    // {

    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            //code...
            $request->validate([
                'projectName' => 'required|min:5',
                'accountId' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message'    => 'Error',
                'status' => 'Failed',
                'errors' => $e->errors(),
            ], 404);
        }
        try {
            //code...
            $account = Account::findOrFail($request->accountId);
            // return($account);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Account not found',
                'status' => 'Failed',
            ], 404);
            //throw $th;
        }
        $projectList = $this->projects->edit($request->projectName, $request->accountId, $id);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Edit successful',
                'status' => 'Success',
            ], 200);
        } else $response = response()->json([
            'message' => 'Edit failed',
            'status' => 'Failed',
        ], 404);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $Project)
    {
        //
    }

    public function deleteById($id)
    {
        $projectList = $this->projects->deleteById($id);
        if ($projectList) {
            $response = response()->json([
                'message' => 'Delete successful',
                'status' => 'Success',
            ], 200);
        } else $response = response()->json([
            'message' => 'Delete failed',
            'status' => 'Failed',
        ], 404);
        return $response;
    }
}
