<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Project\ProjectTaskResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\Project;
use App\Models\ProjectDevStage;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class ProjectTaskController extends Controller
{
    /**
     * get all projects tasks
     */
    public function index(Project $project):JsonResponse
    {
        $tasks = ProjectDevStage::enabled()
            ->with(['tasks' => function($query) use ($project) {
                $query->where('project_id', $project->id)->with('user')
                ->orderBy('position','asc');
            }])
            ->orderBy('position','asc')
            ->get();

        return successResponse(ProjectTaskResource::collection($tasks));
    }

    /**
     * create new task for project
     */
    public function store(CreateTaskRequest $request,Project $project):JsonResponse
    {
         $data = array_merge($request->validated(),['project_id'=>$project->id]);
         $task = Task::create($data);
         return successResponse(TaskResource::make($task));
    }

    /**
     * update task for project
     */
    public function update(UpdateTaskRequest $request,Task $task):JsonResponse
    {
        $task->update($request->validated());
        return successResponse();
    }


    /**
     * delete task for project
     */
    public function destroy(Task $task):JsonResponse
    {
        $task->delete();
        return successResponse();
    }

}
