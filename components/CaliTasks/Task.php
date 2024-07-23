<?php

    namespace CaliTasks;

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliTasks/taskStatus.php');
    require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliTasks/priorityLevel.php');

    class Task
    {
        public int $id;
        public string $taskName;
        public string $taskDescription;
        public \taskStatus $status;
        public \priorityLevel $taskPriority;
        public string $taskDueDate;
        public string $taskStartDate;
        public string $assignedUser;
        private $sql_connection;

        function __construct($con) {

            $this->sql_connection = $con;

        }

        private function _sanitize(string $data): string {

            $con = $this->sql_connection;
            $data = stripslashes($data);
            $data = mysqli_real_escape_string($con, $data);
            return $data;

        }

        private function _query_task_data(string $att_name, string $att_val): ?array {

            $con = $this->sql_connection;
            $query = "SELECT * FROM caliweb_tasks WHERE " . $this->_sanitize($att_name) . " = '" . $this->_sanitize($att_val) . "';";
            $exec = mysqli_query($con, $query);
            $array = mysqli_fetch_array($exec);
            return $array;

        }

        private function _lower_and_clear(string $data): string {

            return str_replace(" ", "", strtolower($data));

        }

        private function _join_and_trim(string $data): string {

            $pieces = preg_split('/(?=[A-Z])/', $data);
            $joined = implode(" ", $pieces);
            $joined = trim($joined);
            return $joined;

        }

        function transformStringToStatusColor(string $requestedString): ?\taskStatus {

            $possible_status_color = array_combine(array_map(fn($item) => $this->_join_and_trim($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
            return $possible_status_color[$requestedString] ?? null;

        }

        function transformPriorityToPriorityColor(\priorityLevel $requestedPriority): ?\taskStatus {

            $reqString = $this->fromPriorityLevel($requestedPriority);
            return $this->transformStringToStatusColor($reqString);

        }

        function fromPriorityLevel(\priorityLevel $requestedPriority): ?string {

            $possible_priorities = array_combine(array_map(fn($item) => $this->_join_and_trim($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());
            $idx = array_search($requestedPriority, $possible_priorities);

            if ($idx === false) {

                return null;

            }

            return $idx;

        }

        function fromTaskStatus(\taskStatus $requestedStatus): ?string {

            $possible_statuses = array_combine(array_map(fn($item) => $this->_join_and_trim($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
            $idx = array_search($requestedStatus, $possible_statuses);

            if ($idx === false) {

                return null;

            }

            return $idx;
        }

        function toPriorityLevel(string $requestedPriority): ?\priorityLevel {

            $possible_priorities = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());
            
            if (!isset($possible_priorities[$this->_lower_and_clear($requestedPriority)])) {

                return null;

            }

            return $possible_priorities[$this->_lower_and_clear($requestedPriority)];

        }

        function toTaskStatus(string $requestedStatus): ?\taskStatus {

            $possible_statuses = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
            
            if (!isset($possible_statuses[$this->_lower_and_clear($requestedStatus)])) {

                return null;

            }

            return $possible_statuses[$this->_lower_and_clear($requestedStatus)];

        }

//        function createTask(array $taskData): bool {
//
//            $con = $this->sql_connection;
//
//            $query = "INSERT INTO `caliweb_tasks` (taskName, taskDescription, status, taskPriority, taskDueDate, taskStartDate, assignedUser) VALUES ('"
//                    . $this->_sanitize($taskData['taskName']) . "', '"
//                    . $this->_sanitize($taskData['taskDescription']) . "', '"
//                    . $this->_sanitize($taskData['status']) . "', '"
//                    . $this->_sanitize($taskData['taskPriority']) . "', '"
//                    . $this->_sanitize($taskData['taskDueDate']) . "', '"
//                    . $this->_sanitize($taskData['taskStartDate']) . "', '"
//                    . $this->_sanitize($taskData['assignedUser']) . "');";
//
//            $exec = mysqli_query($con, $query);
//            return (bool) $exec;
//
//        }

        function updateTask(int $taskId, array $taskData): bool {

            $con = $this->sql_connection;

            $setString = '';

            foreach ($taskData as $key => $value) {

                $setString .= $this->_sanitize($key) . " = '" . $this->_sanitize($value) . "', ";
            }

            $setString = rtrim($setString, ', ');

            $query = "UPDATE `caliweb_tasks` SET " . $setString . " WHERE id = " . $this->_sanitize((string)$taskId) . ";";

            $exec = mysqli_query($con, $query);

            return (bool) $exec;
        }

        function fetchTaskById(int $taskId): bool {

            $data_array = $this->_query_task_data("id", (string)$taskId);
            if (!$data_array) {

                return false;

            }


            $enum_attrs = array(
                "status",
                "taskPriority",
            );

            $possible_task_statuses = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
            $possible_priority_levels = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());

            foreach ($data_array as $key => $value) {
                if (in_array($key, $enum_attrs)) {
                    if ($key == "status") {

                        $statusToBeSet = null;

                        if (!isset($possible_roles[$this->_lower_and_clear($value)])) {

                            $statusToBeSet = $possible_task_statuses["pending"];

                        } else {

                            $statusToBeSet = $possible_task_statuses[$this->_lower_and_clear($value)];

                        }

                        $this->status = $statusToBeSet;

                    } elseif ($key == "taskPriority") {

                        $priorityToBeSet = null;

                        if (!isset($possible_priority_levels[$this->_lower_and_clear($value)])) {

                            $priorityToBeSet = $possible_priority_levels[$this->_lower_and_clear("Normal")];

                        } else {

                            $priorityToBeSet = $possible_priority_levels[$this->_lower_and_clear($value)];

                        }

                        $this->taskPriority = $priorityToBeSet;

                    }

                } else {

                    if ($key != "sql_connection" && !is_int($key)) {

                        $this->{$key} = $value;

                    }

                }

            }
            return true;

        }


//        function deleteTask(int $taskId): bool {
//
//            $con = $this->sql_connection;
//            $query = "DELETE FROM `caliweb_tasks` WHERE id = " . $this->_sanitize((string)$taskId) . ";";
//            $exec = mysqli_query($con, $query);
//            return (bool) $exec;
//
//        }

    }

?>