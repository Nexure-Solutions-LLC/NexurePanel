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

        try {

            $this->sql_connection = $con;
            $this->collectionToQuery = $manager->collectionToQuery;
            $this->primaryIdentifier = $manager->queryingIdentifier;
            $this->primaryIdentifierIsString = $manager->queryingIdentifierIsString;
            $this->manager = $manager;
            $this->helper = new CaliUtilities\StringHelper();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _query_generic_data_by_primary(string $att_val): ?array {

        try {

            $con = $this->sql_connection;
            $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $this->primaryIdentifier) . " = ". ($this->primaryIdentifierIsString ? "'" : "") ."" . $this->helper->sanitize($con, $att_val) . "". ($this->primaryIdentifierIsString ? "'" : "") .";";
            return $con->query($query)->fetch_array();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _query_generic_data_by_specified_attribute(string $att_name, string $att_val): ?array {

        try {

            $con = $this->sql_connection;
            $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $att_name) . " = '" . $this->helper->sanitize($con, $att_val) . "';";
            return $con->query($query)->fetch_array();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    public function fetchByPrimaryIdentifier(string $identifierValue) {

        try {

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

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function isSetup(): bool {

        try {

            return isset($this->{$this->primaryIdentifier});

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    public function Refresh(): bool {

        try {

            if (!$this->isSetup()) {
                
                return false;

            }

            return $this->fetchByPrimaryIdentifier($this->{$this->primaryIdentifier});

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    public function UpdateAttr(string $att_name, $att_val, bool $rawDataisString): bool {

        try {

            if (!$this->isSetup()) {

                return false;

            }

            // always refresh data before updating

            $isRefreshed = $this->Refresh();

            if (!$isRefreshed) {

                return false;

            }

            if (gettype($att_val) == "object") {

                $att_val = $this->helper->join_and_trim($att_val->name);

            }

            $query = "UPDATE `$this->collectionToQuery` SET $this->helper->sanitize($this->sql_connection, $att_name) = " . ($rawDataisString ? "'" : "") . $this->helper->sanitize($this->sql_connection, $att_val) . ($rawDataisString ? "'" : "") . " WHERE $this->helper->sanitize($this->sql_connection, $this->primaryIdentifier) = " . ($this->primaryIdentifierIsString ? "'" : "") . $this->helper->sanitize($this->sql_connection, $this->{$this->primaryIdentifier}) . ($this->primaryIdentifierIsString ? "'" : "") . ";";
            $this->sql_connection->query($query);
            return $this->Refresh();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    public function MultiUpdateAttr(array $massDataArray): bool {

        try {

            // Example data structure:
            // array( [0] => array(attName, attVal, isString), ... )

            if (!$this->isSetup()) {

                return false;

            }

            // always refresh data before updating

            $isRefreshed = $this->Refresh();

            if (!$isRefreshed) {

                return false;

            }

            $baseQuery = "UPDATE `$this->collectionToQuery` SET ";

            if (count($massDataArray) == 0) {

                // this command can not operate when no attributes are being change
                // this is to prevent an invalid sql command from being executed
                // on the server

                return false;
            }


            foreach ($massDataArray as $index => $data_array) {
                $attName = $data_array[0];
                $attValue = $data_array[1];
                $isString = $data_array[2];

                $baseQuery = $baseQuery . $this->helper->sanitize($this->sql_connection, $attName) . " = " . ($isString ? "'" : "") . $this->helper->sanitize($this->sql_connection, $attValue) . ($isString ? "'" : "") . ($index == (count($massDataArray) - 1) ? ", " : " ");
            
            };

            // $baseQuery = $baseQuery . "WHERE " . $this->helper->sanitize()

            return true;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }
    
}

?>