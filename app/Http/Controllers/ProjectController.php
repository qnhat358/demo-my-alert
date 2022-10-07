<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Http\Requests\ProjectRequest;
use App\Services\Project\ProjectServiceInterface;
use Exception;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectServiceInterface $ProjectService)
    {
        $this->projectService = $ProjectService;
    }

    public function index()
    {
        return $this->projectService->getAll();
    }

    public function getById($id)
    {
        return $this->projectService->get($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        return $this->projectService->create($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $Project
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectRequest $request, $id)
    {
        return $this->projectService->update($request, $id);
    }

    public function deleteById($id)
    {
        return $this->projectService->delete($id);
    }
}
