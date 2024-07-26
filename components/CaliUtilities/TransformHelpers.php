<?php

namespace CaliUtilities;

class TransformHelpers
{

    protected StringHelper $helper;

    function __construct()
    {

        $this->helper = new StringHelper();
        
    }


    function transformStringToStatusColor(string $requestedString): ?\taskStatus {

        $possible_status_color = array_combine(array_map(fn($item) => $this->helper->join_and_trim($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
        return $possible_status_color[$requestedString] ?? null;

    }

    function transformPriorityToPriorityColor(\priorityLevel $requestedPriority): ?\taskStatus {

        $reqString = $this->fromPriorityLevel($requestedPriority);
        return $this->transformStringToStatusColor($reqString);

    }

    function fromPriorityLevel(\priorityLevel $requestedPriority): ?string {

        $possible_priorities = array_combine(array_map(fn($item) => $this->helper->join_and_trim($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());
        $idx = array_search($requestedPriority, $possible_priorities);

        if ($idx === false) {

            return null;

        }

        return $idx;

    }

    function fromTaskStatus(\taskStatus $requestedStatus): ?string {

        $possible_statuses = array_combine(array_map(fn($item) => $this->helper->join_and_trim($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
        $idx = array_search($requestedStatus, $possible_statuses);

        if ($idx === false) {

            return null;

        }

        return $idx;
    }

    function toPriorityLevel(string $requestedPriority): ?\priorityLevel {

        $possible_priorities = array_combine(array_map(fn($item) => $this->helper->lower_and_clear($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());

        if (!isset($possible_priorities[$this->helper->lower_and_clear($requestedPriority)])) {

            return null;

        }

        return $possible_priorities[$this->helper->lower_and_clear($requestedPriority)];

    }

    function toTaskStatus(string $requestedStatus): ?\taskStatus {

        $possible_statuses = array_combine(array_map(fn($item) => $this->helper->lower_and_clear($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());

        if (!isset($possible_statuses[$this->helper->lower_and_clear($requestedStatus)])) {

            return null;

        }

        return $possible_statuses[$this->helper->lower_and_clear($requestedStatus)];

    }

}

?>