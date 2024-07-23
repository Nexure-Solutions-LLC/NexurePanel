<?php

namespace CaliTasks;

use mysqli;

include($_SERVER["DOCUMENT_ROOT"] . "/components/CaliTasks/Task.php");


class TaskManager {
    // Task Manager allows for routine administration of CRUD
    // operations on tasks, as well as keeping an internal list
    // of all the tasks that are in the database.
    //
    // This is so that the code can get all of the task objects
    // as well as perform actions that effect more than one task.

    private array $tasks;
    private mysqli $sql_connection;

    function __construct(mysqli $sql_connection) {

        $this->sql_connection = $sql_connection;

    }

    function getAllTasks(): array
    {

        $fixed_tasks = array();

        foreach ($this->tasks as $key => $value) {

            $fixed_tasks[$value->id] = $value;

        }

        return $fixed_tasks;

    }

    function getTasksBySpecifiedAttributes(array $attributes): array
    {

        $tasks = $this->getAllTasks();
        $exempt_tasks = array();

        foreach ($attributes as $key => $value) {

            foreach ($tasks as $index => $task) {

                if (!isset($task->{$key})) {

                    continue;

                }

                if ($task->{$key} != $value) {

                    if (in_array($task, $exempt_tasks)) {

                        $exempt_tasks[] = $task;

                    }

                }

            }

        }

        $final_array = array();

        foreach ($tasks as $i => $t) {

            if (!in_array($t, $exempt_tasks)) {

                $final_array[$t->id] = $t;

            }

        }

        return $final_array;

    }

    function upsertInternalTaskById(int $task_id): Task {

        $con = $this->sql_connection;
        $tasks = $this->tasks;

        if (array_key_exists($task_id, $tasks)) {

            return $tasks[$task_id];

        }

        $task = new Task($con);
        $task->fetchTaskById($task_id);
        $tasks[$task->id] = $task;

        return $task;

    }
    
}


?>