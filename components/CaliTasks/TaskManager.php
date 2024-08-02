<?php

namespace CaliTasks;

use CaliGenerics\GenericManager;
use mysqli;

include($_SERVER["DOCUMENT_ROOT"] . "/components/CaliTasks/Task.php");


class TaskManager extends GenericManager {

    // Task Manager allows for routine administration of CRUD
    // operations on tasks, as well as keeping an internal list
    // of all the tasks that are in the database.
    // This is so that the code can get all of the task objects
    // as well as perform actions that effect more than one task.

    function __construct(mysqli $sql_connection) {

        try {

            parent::__construct(
                $sql_connection,
                array(
                    "status" => array(0 => \taskStatus::class, 1 => "Pending"),
                    "taskPriority" => array(0 => \priorityLevel::class, 1 => "Normal")
                )
            );

            $this->_setQueryingIdentifier("id");
            $this->queryingIdentifierIsString = false;
            $this->InheritableSubclass = Task::class;
            $this->_setCollectionToQuery("caliweb_tasks");

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    private function _sanitize(string $data): string {

        try {

            $con = $this->sql_connection;
            $data = stripslashes($data);
            $data = mysqli_real_escape_string($con, $data);
            return $data;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    private function _idQuery(int $task_id): ?array {

        try {

            $query = "SELECT * FROM `caliweb_tasks` WHERE id = $this->id;";
            $con = $this->sql_connection;
            $exec = $con->query($query);
            return $exec->fetch_array() ?? null;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    private function _allQuery(): ?array {

        try {

            $con = $this->sql_connection;
            $query = "SELECT * FROM `caliweb_tasks`";
            $exec = $con->query($query);
            return $exec->fetch_all();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    public function hasBeenFetched(): bool
    {

        try {

            return $this->isFetched;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    function getAllTasks(): array
    {

        try {

            $this->fetchAllGenerics();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

        
    }

    function getTasksBySpecifiedAttributes(array $attributes): array
    {

        try {

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

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    function upsertInternalTaskById(int $task_id): Task {

        try {

            $con = $this->sql_connection;
            $tasks = $this->tasks;

            if (array_key_exists($task_id, $tasks)) {

                return $tasks[$task_id];

            }

            $task = new Task($con);
            $task->fetchTaskById($task_id);
            $tasks[$task->id] = $task;

            return $task;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }
    
}


?>