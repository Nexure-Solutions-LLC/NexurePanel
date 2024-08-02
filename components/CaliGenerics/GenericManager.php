<?php

namespace CaliGenerics;

use CaliUtilities\StringHelper;
use Hoa\File\Generic;
use mysqli;

class GenericManager
{
    
    // Management of Generic subclasses.
    // Management classes will inherit from this class.
    // (CaliTasks, CaliCases, CaliLeads, CaliCampaigns, CaliEmployees)

    protected mysqli $sql_connection;
    public string $collectionToQuery;
    public string $queryingIdentifier;
    protected array $generics;
    public StringHelper $helper;
    public array $schema;
    protected $InheritableSubclass = GenericInheritable::class;
    public bool $queryingIdentifierIsString = true;

    function __construct(mysqli $sql_connection, array $schema) {

        try {

            $this->schema = $schema;
            $this->sql_connection = $sql_connection;
            $this->helper = new StringHelper();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _setQueryingIdentifier(string $queryIdentifier) {

        try {

            $this->queryingIdentifier = $queryIdentifier;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _setCollectionToQuery(string $collection) {

        try {

            $this->collectionToQuery = $collection;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _setQueryingIdentifierType(bool $isString) {

        try {

            $this->queryingIdentifierIsString = $isString;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }



    private function isSetup(): bool {

        try {

            return (isset($this->collectionToQuery) && isset($this->queryingIdentifier));

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _query_main_identifier(string $att_val): ?array {

        try {

            if (!$this->isSetup()) {

                return null;

            }

            $con = $this->sql_connection;
            $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $this->queryingIdentifier) . " = ". ($this->queryingIdentifierIsString ? "'" : "") ."" . $this->helper->sanitize($con, $att_val) . "". ($this->queryingIdentifierIsString ? "'" : "") .";";
            return $con->query($query)->fetch_array();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function _queryAllData(): ?array {

        try {

            $con = $this->sql_connection;
            $query = "SELECT * FROM `$this->collectionToQuery`";
            $exec = $con->query($query);
            return $exec->fetch_all();

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function fetchAllGenerics(): bool {

        try {

            if (!$this->isSetup()) {

                // GenericManager needs to be setup to function
                // correctly.

                return false;

            }

            if (count($this->generics) != 0) {

                // Fetch all generics should not be used in a case that isn't
                // of initial fetching.

                return false;

            }

            $all_data = $this->_queryAllData();

            foreach ($all_data as $_ => $generic) {

                $GenericItem = new $this->InheritableSubclass(

                    $this->sql_connection, $this

                );

                $GenericItem->fetchByPrimaryIdentifier($generic[$this->queryingIdentifier]);

                $this->generics[$generic[$this->queryingIdentifier]] = $GenericItem;

            }

            return true;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

    protected function getOneGenericByPrimaryIdentifier(string $att_val) {

        try {
        
            return $this->generics[$att_val] ?? null;

        } catch (\Throwable $exception) {
            
            \Sentry\captureException($exception);
        
        }

    }

}

?>