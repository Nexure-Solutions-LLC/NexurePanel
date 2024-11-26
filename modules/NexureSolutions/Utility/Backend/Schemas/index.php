<?php

    enum accessLevel {
        case Executive;
        case Retail;
        case Manager;
    }

    enum accountStatus {
        case Active;
        case UnderReview;
        case Closed;
        case Suspended;
        case Terminated;
        case Restricted;
    }

    enum statusColor: string {
        case Active = "green";
        case UnderReview = "yellow";
        case Closed = "passive";
        case Suspended = "red";
        case Terminated = "red-dark";
        case Restricted = "red-restricted";
    }

    enum userRole {
        case Administrator;
        case Customer;
        case AuthorizedUser;
        case Partner;
    }

    enum taskStatus
    {
        case Completed;
        case Pending;
        case Closed;
        case OverDue;
        case Stuck;
    }

    enum priorityLevel {
        case Highest;
        case Elevated;
        case Normal;
    }

?>