<?php

namespace CaliUtilities;

use mysqli;

class StringHelper
{
    public function lower_and_clear(string $data): string {
        return str_replace(" ", "", strtolower($data));
    }

    public function join_and_trim(string $data): string {

        $pieces = preg_split('/(?=[A-Z])/', $data);
        $joined = implode(" ", $pieces);
        $joined = trim($joined);
        return $joined;

    }

    public function sanitize(mysqli $con, string $data): string {
        $data = stripslashes($data);
        $data = mysqli_real_escape_string($con, $data);
        return $data;
    }
}