<?php

    namespace CaliTasks;

    use CaliGenerics\GenericInheritable;
    use CaliUtilities\StringHelper;
    use CaliUtilities\TransformHelpers;

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliTasks/taskStatus.php');
    require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliTasks/priorityLevel.php');

    class Task extends GenericInheritable
    {
        public int $id;
        public string $taskName;

        public TransformHelpers $transforms;
        public StringHelper $helper;
        public string $taskDescription;
        public \taskStatus $status;
        public \priorityLevel $taskPriority;
        public string $taskDueDate;
        public string $taskStartDate;
        public string $assignedUser;

        function __construct($con, $manager)
        {
            parent::__construct($con, $manager);
            // primaryIdentifier may not be used at this time
            // because I don't think it supports non-string keys
            // however when it is added it should become more mainstream for
            // fetching-by-primary-identifier
        }


        function updateTask(int $taskId, array $taskData): bool {

            $con = $this->sql_connection;

            $setString = '';

            foreach ($taskData as $key => $value) {

                $setString .= $this->helper->sanitize($con, $key) . " = '" . $this->helper->sanitize($con, $value) . "', ";
            }

            $setString = rtrim($setString, ', ');

            $query = "UPDATE `caliweb_tasks` SET " . $setString . " WHERE id = " . $this->helper->sanitize($con, (string)$taskId) . ";";

            $exec = mysqli_query($con, $query);

            return (bool) $exec;
        }

        function refresh(): bool {
            if (!isset($this->id)) {
                return false;
            }
            $this->fetchTaskById($this->id);
            return true;
        }

    }

?>