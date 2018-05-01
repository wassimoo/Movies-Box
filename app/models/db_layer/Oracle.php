<?php

/**
 * Description of SQLDatabase
 *
 * @author sergo.beruashvili
 * @author Wassim Bougarfa
 */

namespace MoviesBox;


require "DBCException.php";


class Oracle
{

    private $host;
    private $port;
    private $charset;
    private $schema;

    private $connection;

    /**
     * Create connection instance.
     * Specifing no arguments connects you to localhost
     *
     * @var string $host
     * @var int $port
     * @var string $charset
     */
    public function __construct($host = "localhost/XE", $port = 1521, $charset = "AL32UTF8")
    {
        $this->host = $host;
        $this->port = $port;
        $this->charset = $charset;
        $this->connection = null;
    }

    /**
     * Connect to database using specified username and password
     * @var string $username
     * @var string $password
     * @var bool $isSysdba set to true if user is sysdba
     * @return bool success
     * @throws DBCException on failure.
     */
    public function connect($username, $password, $isSysdba = false, $schema = null)
    {
        if ($this->host == "localhost/XE") {
            $connectionStr = "localhost/XE:" . $this->port;
        } else {
            $connectionStr = "( DESCRIPTION= ( ADDRESS_LIST = ( ADDRESS = (PROTOCOL = TCP)(HOST = $this->host )(PORT = $this->port) ) ) ) )";
        }

        if (empty($username)) {
            throw new DBCException("No username has been specified", 10101);
        }
        if ($isSysdba) {
            $conn = oci_connect($username, $password, $connectionStr, $this->charset, OCI_SYSDBA);
        } else {
            $conn = oci_connect($username, $password, $connectionStr, $this->charset);
        }

        if ($conn == false) {
            throw new DBCException("Can't connect to database. Error returned " . oci_error()["message"], 10102);
        }

        $this->connection = $conn;

        if (!empty($schema)) {
            try{
                $this->switchSchema($schema);
            }catch(DBCException $e){
                $this->connection = null;
                $msg = "connection established succefully but couldn't switch schema.
                Original error : " . $e->getMessage();
                throw new DBCException($msg, 10105);
            }
        }

        return true;
    }

    /**
     * Change current Schema.
     * @var $schemaName
     * @return bool on success
     * @throws DBCException
     */
    public function switchSchema($schemaName)
    {
        if ($this->connection == null) {
            throw new DBCException("connection not established try callign connect()", 10103);
        }

        //get current schema;
        $query = "SELECT SYS_CONTEXT('userenv', 'current_schema' ) FROM dual";
        $stid = oci_parse($this->connection, $query);
        if (oci_execute($stid, OCI_NO_AUTO_COMMIT) == false) {
            throw new DBCException("Couldn't get current schema. Error returned " . oci_error()["message"], 10104);
        } else if (oci_fetch_row($stid)[0] != strtoupper($schemaName)) {
            //change schema.
            $query = "ALTER SESSION SET CURRENT_SCHEMA = " . $schemaName;
            $stid = oci_parse($this->connection, $query);
            if (oci_execute($stid, OCI_NO_AUTO_COMMIT) == false) {
                throw new DBCException("Couldn't switch to schema { $schemaName }. Error returned ". oci_error()["message"], 10105);
            }
        }
        $this->schema = $schemaName;
        return true;
    }

    /*
     *
     * Takes $sql statement and $values containing key => value binding params
     * Returns Array (succes,data)
     *  success - boolean - true/false , if query executed
     *  data    - mixed - array of rows on success-true , error info on succes-false
     */

    public function qin($sql, $values = array())
    {
        $statement = oci_parse($this->connection, $sql);

        foreach ($values as $key => $val) {
            oci_bind_by_name($statement, $key, $val, 512);
        }

        if (@!oci_execute($statement)) {
            $errors = oci_error($statement);
            return array('success' => false, 'data' => 'Error : ' . $errors['code'] . ' => ' . $errors['message']);
        }

        $result = array();
        oci_fetch_all($statement, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        return array('success' => true, 'data' => $result);
    }

    /*
     * Takes sql statement and $values ,returns boolean , succes/failure of execute ( eg UPDATE , INSERT ... )
     *
     */

    public static function qout($sql, $values = array())
    {

        $statement = oci_parse($this->connection, $sql);

        foreach ($values as $key => $val) {
            oci_bind_by_name($statement, $key, $val, 512);
        }

        if (@!oci_execute($statement)) {
            $errors = oci_error($statement);
            return array('success' => false, 'data' => 'Error : ' . $errors['code'] . ' => ' . $errors['message']);
        }

        return array('success' => true, 'data' => '');
    }

    /*
     * Call the procedure , return array of success/true , and params filled up with OUT data ( if available )
     */

    public static function callProcedure($procedure, $values = array())
    {
        $sql = '';

        $keys = array_keys($values);

        if (sizeof($values) > 0) {
            $sql = 'BEGIN ' . $procedure . '(' . implode(',', $keys) . '); END;';
        } else {
            $sql = 'BEGIN ' . $procedure . '; END;';
        }

        $statement = oci_parse($this->connection, $sql);

        foreach ($keys as $key) {
            oci_bind_by_name($statement, $key, $values[$key], 512);
        }

        if (@!oci_execute($statement)) {
            $errors = oci_error($statement);
            return array('success' => false, 'data' => 'Error : ' . $errors['code'] . ' => ' . $errors['message'], 'params' => $values);
        }

        return array('success' => true, 'data' => '', 'params' => $values);
    }

    /*
     * Call the function and , return arra of success/true , data returned by function and params filled up with OUT data ( if available )
     */

    public static function callFunction($procedure, $values = array())
    {


        $sql = '';

        $keys = array_keys($values);

        $result = null;

        if (sizeof($values) > 0) {
            $sql = 'BEGIN :callFunctionRes := ' . $procedure . '(' . implode(',', $keys) . '); END;';
        } else {
            $sql = 'BEGIN :callFunctionRes := ' . $procedure . '; END;';
        }

        $statement = oci_parse($this->connection, $sql);

        oci_bind_by_name($statement, ':callFunctionRes', $result, 512);

        foreach ($keys as $key) {
            oci_bind_by_name($statement, $key, $values[$key], 512);
        }

        if (@!oci_execute($statement)) {
            $errors = oci_error($statement);
            return array('success' => false, 'data' => 'Error : ' . $errors['code'] . ' => ' . $errors['message'], 'params' => $values);
        }

        return array('success' => true, 'data' => $result, 'params' => $values);
    }

    /*
     * Call the function and , return arra of success/true , data returned by function and params filled up with OUT data ( if available )
     */

    public static function callCursorFunction($procedure, $values = array())
    {


        $sql = '';

        $keys = array_keys($values);

        $p_cursor = oci_new_cursor($this->connection);

        if (sizeof($values) > 0) {
            $sql = 'BEGIN :callFunctionRes := ' . $procedure . '(' . implode(',', $keys) . '); END;';
        } else {
            $sql = 'BEGIN :callFunctionRes := ' . $procedure . '; END;';
        }

        $statement = oci_parse($this->connection, $sql);

        oci_bind_by_name($statement, ':callFunctionRes', $p_cursor, -1, OCI_B_CURSOR);

        foreach ($keys as $key) {
            oci_bind_by_name($statement, $key, $values[$key], 512);
        }

        if (@!oci_execute($statement)) {
            $errors = oci_error($statement);
            return array('success' => false, 'data' => 'Error : ' . $errors['code'] . ' => ' . $errors['message'], 'params' => $values);
        }

        oci_execute($p_cursor);

        $result = array();
        oci_fetch_all($p_cursor, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        return array('success' => true, 'data' => $result, 'params' => $values);
    }
}