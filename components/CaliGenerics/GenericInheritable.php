<?php

namespace CaliGenerics;

use mysqli;
use CaliUtilities;

class GenericInheritable
{

    protected mysqli $sql_connection;
    protected string $collectionToQuery;
    protected string $primaryIdentifier;
    protected GenericManager $manager;
    protected CaliUtilities\StringHelper $helper;

    function __construct(mysqli $con, GenericManager $manager) {

        $this->sql_connection = $con;
        $this->collectionToQuery = $manager->collectionToQuery;
        $this->primaryIdentifier = $manager->queryingIdentifier;
        $this->primaryIdentifierIsString = $manager->queryingIdentifierIsString;
        $this->manager = $manager;
        $this->helper = new CaliUtilities\StringHelper();

    }

    protected function _query_generic_data_by_primary(string $att_val): ?array {
        $con = $this->sql_connection;
        $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $this->primaryIdentifier) . " = ". ($this->primaryIdentifierIsString ? "'" : "") ."" . $this->helper->sanitize($con, $att_val) . "". ($this->primaryIdentifierIsString ? "'" : "") .";";
        return $con->query($query)->fetch_array();
    }

    protected function _query_generic_data_by_specified_attribute(string $att_name, string $att_val): ?array {
        $con = $this->sql_connection;
        $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $att_name) . " = '" . $this->helper->sanitize($con, $att_val) . "';";
        return $con->query($query)->fetch_array();
    }

    public function fetchByPrimaryIdentifier(string $identifierValue) {
        $schema = $this->manager->schema;
        $variable_attrs = array_keys($schema);
        $attrs_to_types = array_combine($variable_attrs, array_column(array_values($schema), 0));
        $attrs_to_defaults = array_combine($variable_attrs, array_column(array_values($schema), 1));

        $data_array = $this->_query_task_data($this->primaryIdentifier, (string)$identifierValue);
        if (!$data_array) {
            return false;
        }

        $key_possibilities = array();

        foreach ($attrs_to_types as $attr => $type) {
            $key_possibilities[$attr] = array_combine(array_map(fn($item) => $this->helper->lower_and_clear($item), array_column($type::cases(), 'name')), $type::cases());
        }


        foreach ($data_array as $key => $value) {
            if (in_array($key, $variable_attrs)) {
                $possibles = $key_possibilities[$key];
                $this->{$key} = isset($possibles[$this->helper->lower_and_clear($value)]) ? $possibles[$this->helper->lower_and_clear($value)] : $possibles[$this->helper->lower_and_clear($attrs_to_defaults[$key])];
            } else {
                if ($key != "sql_connection" && !is_int($key)) {
                    $this->{$key} = $value;
                }
            }

        }
        return true;
    }



}

?>