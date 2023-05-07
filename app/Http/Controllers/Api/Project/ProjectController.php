<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\CreateProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * get all projects
     */
    public function index():JsonResponse
    {
        $projects = Project::latest()->get();
        return successResponse(ProjectResource::collection($projects));
    }


    /**
     * create new Project
     */
    public function store(CreateProjectRequest $request):JsonResponse
    {
        Project::create($request->Validated());
        return successResponse();
    }

    /**
     * Display a project
     */
    public function show(Project $project):JsonResponse
    {
        return successResponse(ProjectResource::make($project));
    }



    /**
     * Update a project
     */
    public function update(UpdateProjectRequest $request, Project $project):JsonResponse
    {
        $project->update($request->validated());
        return successResponse();
    }

    /**
     * delete a project
     */
    public function destroy(Project $project):JsonResponse
    {
        return successResponse();
    }
}
