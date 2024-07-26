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
        $this->schema = $schema;
        $this->sql_connection = $sql_connection;
        $this->helper = new StringHelper();
    }

    protected function _setQueryingIdentifier(string $queryIdentifier) {
        $this->queryingIdentifier = $queryIdentifier;
    }

    protected function _setCollectionToQuery(string $collection) {
        $this->collectionToQuery = $collection;
    }

    protected function _setQueryingIdentifierType(bool $isString) {
        $this->queryingIdentifierIsString = $isString;
    }



    private function isSetup(): bool {
        return (isset($this->collectionToQuery) && isset($this->queryingIdentifier));
    }

    protected function _query_main_identifier(string $att_val): ?array {
        if (!$this->isSetup()) {
            return null;
        }
        $con = $this->sql_connection;
        $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $this->queryingIdentifier) . " = ". ($this->queryingIdentifierIsString ? "'" : "") ."" . $this->helper->sanitize($con, $att_val) . "". ($this->queryingIdentifierIsString ? "'" : "") .";";
        return $con->query($query)->fetch_array();
    }

    protected function _queryAllData(): ?array {
        $con = $this->sql_connection;
        $query = "SELECT * FROM `$this->collectionToQuery`";
        $exec = $con->query($query);
        return $exec->fetch_all();
    }

    protected function fetchAllGenerics(): bool {
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
    }

    protected function getOneGenericByPrimaryIdentifier(string $att_val) {
        return $this->generics[$att_val] ?? null;
    }





}

?>