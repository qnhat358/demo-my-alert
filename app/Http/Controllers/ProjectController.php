<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

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
        $request->validate([
            'projectName' => 'required|min:5',
            'accountId' => 'required|integer'
        ]);
        $projectList = $this->projects->add($request->projectName, $request->accountId);
        return 'Add OK';
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
    public function edit(Request $request)
    {
        $request->validate([
            'projectName' => 'required|min:5',
            'accountId' => 'required|integer',
            'id' => 'required|integer',
        ]);
        $projectList = $this->projects->update($request->projectName, $request->accountId, $request->id);
        return 'Update OK';
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
}
