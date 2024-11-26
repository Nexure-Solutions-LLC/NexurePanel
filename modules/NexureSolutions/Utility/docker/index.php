<?php

    // Cali Panel will now run applications in docker.
    // This is the module responsible for handling docker functions
    // This file will do the basics needed to use docker with Cali Web Design.

    namespace NexureSolutions\Docker;

    class DockerMain
    {
        private $dockerCliPath;

        public function __construct($dockerCliPath = '/usr/bin/docker')
        {
            $this->dockerCliPath = $dockerCliPath;
        }

        public function runContainer($image, $containerName, $portMapping = [], $envVars = [])
        {
            $command = "{$this->dockerCliPath} run -d --name {$containerName}";

            foreach ($portMapping as $hostPort => $containerPort) {
                $command .= " -p {$hostPort}:{$containerPort}";
            }

            foreach ($envVars as $key => $value) {
                $command .= " -e {$key}={$value}";
            }

            $command .= " {$image}";

            return $this->executeCommand($command);
        }

        public function stopContainer($containerName)
        {
            $command = "{$this->dockerCliPath} stop {$containerName}";
            return $this->executeCommand($command);
        }

        public function removeContainer($containerName)
        {
            $command = "{$this->dockerCliPath} rm {$containerName}";
            return $this->executeCommand($command);
        }

        public function pullImage($image)
        {
            $command = "{$this->dockerCliPath} pull {$image}";
            return $this->executeCommand($command);
        }

        public function listContainers($all = false)
        {
            $command = "{$this->dockerCliPath} ps" . ($all ? " -a" : "");
            return $this->executeCommand($command);
        }

        private function executeCommand($command)
        {
            exec($command, $output, $returnVar);
            return [
                'output' => $output,
                'status' => $returnVar
            ];
        }
    }

?>