<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Requests\V1\Tasks\TasksStoreRequest;
use App\Http\Requests\V1\Tasks\TasksUpdateRequest;

class TaskController extends Controller
{
    use ApiResponder;

    function getById($id)
    {
        if ($task = Task::find($id)) {
            return $this->jsonSuccess($task);
        }
        return $this->jsonError('Nothing found at id ' . $id . '.');
    }

    function get()
    {
        if ($tasks = Task::get()) {
            return $this->jsonSuccess($tasks);
        }
        return $this->jsonError('Nothing found.');

    }

    function getActiveTasksNumber ()
    {
        if ($activeTasksCount = Task::where('status', '!=', '0')->count()) {
            return $this->jsonSuccess($activeTasksCount);
        }
        return $this->jsonError('Nothing found.');
    }

    function getActiveTasks ()
    {
        if ($activeTasks = Task::where('status', '!=', '0')->get()) {
            return $this->jsonSuccess($activeTasks);
        }
        return $this->jsonError('Nothing found.');
    }

    function getArchivedTasks ()
    {
        if ($archivedTasks = Task::where('status', '=', '0')->get()) {
            return $this->jsonSuccess($archivedTasks);
        }
        return $this->jsonError('Nothing found.');
    }

    /**
     * create task
     */
    function store(TasksStoreRequest $request)
    {
        $tasks = Task::create($request->all());
        if ($tasks) {
            return $this->jsonSuccess($tasks);
        }
        return $this->jsonError('Something went wrong', 409);
    }

    /**
     * update task
     */
    public function update(TasksUpdateRequest $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return $this->jsonError('Something is wrong, please check datas - Code B30', 409);
        }

        $updatedTask = $task->update($request->all());

        if(!$updatedTask) {
            return $this->jsonError('Could not update this item - Code R31', 502);
        }

        return $this->jsonSuccess($updatedTask);

    }

    /**
     * delete task
     */
    function delete($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return $this->jsonError('Something is wrong, please check datas - Code B30', 409);
        }
        $archivedTask = $task->update(['archived_at' => new \DateTime(), 'status' => 0]);

        if(!$archivedTask) {
            return $this->jsonError('Could not update this item - Code R31', 502);
        }

        return $this->jsonSuccess($archivedTask);
    }
}
