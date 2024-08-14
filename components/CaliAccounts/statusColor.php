<?php

enum statusColor: string
{
    case Active = "green";
    case UnderReview = "yellow";
    case Closed = "passive";
    case Suspended = "red";
    case Terminated = "red-dark";
    case Restricted = "red-restricted";
}

?>