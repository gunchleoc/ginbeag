<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);

$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot ."config.php";
require_once $projectroot ."includes/constants.php";

// security check: restrict which calling scripts get access to the database
$allowedscripts = array(
    'admin/activate.php' => 1,
    'admin/admin.php' => 1,
    'admin/edit/articleedit.php' => 1,
    'admin/edit/galleryedit.php' => 1,
    'admin/edit/linklistedit.php' => 1,
    'admin/edit/menuedit.php' => 1,
    'admin/edit/newsedit.php' => 1,
    'admin/edit/pageintrosettingsedit.php' => 1,
    'admin/editcategories.php' => 1,
    'admin/editimagelist.php' => 1,
    'admin/includes/ajax/articles/addcategories.php' => 1,
    'admin/includes/ajax/articles/removecategories.php' => 1,
    'admin/includes/ajax/articles/savesectiontitle.php' => 1,
    'admin/includes/ajax/articles/savesource.php' => 1,
    'admin/includes/ajax/articles/updatecategories.php' => 1,
    'admin/includes/ajax/articles/updatesectiontitle.php' => 1,
    'admin/includes/ajax/editor/collapseeditor.php' => 1,
    'admin/includes/ajax/editor/editorcontentssavedialog.php' => 1,
    'admin/includes/ajax/editor/expandeditor.php' => 1,
    'admin/includes/ajax/editor/formatpreviewtext.php' => 1,
    'admin/includes/ajax/editor/getserverprotocol.php' => 1,
    'admin/includes/ajax/editor/gettextfromdatabase.php' => 1,
    'admin/includes/ajax/editor/savetext.php' => 1,
    'admin/includes/ajax/galleries/saveimage.php' => 1,
    'admin/includes/ajax/galleries/updateimage.php' => 1,
    'admin/includes/ajax/imageeditor/saveimagefilename.php' => 1,
    'admin/includes/ajax/imageeditor/saveimagealignment.php' => 1,
    'admin/includes/ajax/imageeditor/saveimagesize.php' => 1,
    'admin/includes/ajax/imageeditor/showimagealignment.php' => 1,
    'admin/includes/ajax/imageeditor/showimagesize.php' => 1,
    'admin/includes/ajax/imageeditor/updateimage.php' => 1,
    'admin/includes/ajax/imagelist/addcategories.php' => 1,
    'admin/includes/ajax/imagelist/getimageusage.php' => 1,
    'admin/includes/ajax/imagelist/removecategories.php' => 1,
    'admin/includes/ajax/imagelist/savedescription.php' => 1,
    'admin/includes/ajax/imagelist/updatecategories.php' => 1,
    'admin/includes/ajax/imagelist/updateimage.php' => 1,
    'admin/includes/ajax/linklists/' => 1,
    'admin/includes/ajax/linklists/' => 1,
    'admin/includes/ajax/linklists/savelinkproperties.php' => 1,
    'admin/includes/ajax/linklists/updatelinktitle.php' => 1,
    'admin/includes/ajax/menus/movepage.php' => 1,
    'admin/includes/ajax/menus/saveoptions.php' => 1,
    'admin/includes/ajax/menus/updatesubpages.php' => 1,
    'admin/includes/ajax/news/addcategories.php' => 1,
    'admin/includes/ajax/news/publish.php' => 1,
    'admin/includes/ajax/news/removecategories.php' => 1,
    'admin/includes/ajax/news/savedate.php' => 1,
    'admin/includes/ajax/news/savepermissions.php' => 1,
    'admin/includes/ajax/news/savesectiontitle.php' => 1,
    'admin/includes/ajax/news/savesource.php' => 1,
    'admin/includes/ajax/news/savetitle.php' => 1,
    'admin/includes/ajax/news/unpublish.php' => 1,
    'admin/includes/ajax/news/updatecategories.php' => 1,
    'admin/includes/ajax/news/updatedate.php' => 1,
    'admin/includes/ajax/news/updatesectiontitle.php' => 1,
    'admin/includes/ajax/news/updatetitle.php' => 1,
    'admin/includes/pagelist.php' => 1,
    'admin/includes/preview.php' => 1,
    'admin/login.php' => 1,
    'admin/pagedelete.php' => 1,
    'admin/pagedisplay.php' => 1,
    'admin/pageedit.php' => 1,
    'admin/pagenew.php' => 1,
    'admin/profile.php' => 1,
    'admin/register.php' => 1,
    'admin/showimage.php' => 1,
    'cleanup.php' => 1,
    'contact.php' => 1,
    'guestbook.php' => 1,
    'index.php' => 1,
    'login.php' => 1,
    'rss.php' => 1,
    'showimage.php' => 1,
    'stuth/geamannan/bs/index.php' => 1,
    'stuth/geamannan/crochadair/getword.php' => 1,
    'stuth/geamannan/crochadair/index.php' => 1,
    'stuth/geamannan/leacan/index.php' => 1,
    'stuth/geamannan/leumadair/index.php' => 1,
    'stuth/geamannan/longan/index.php' => 1,
    'stuth/geamannan/matamataigs/index.php' => 1,
    'stuth/geamannan/tetris/highscore.php' => 1,
    'stuth/geamannan/tetris/index.php' => 1,
    'stuth/geamannan/tt/getpuzzle.php' => 1
);

$server_script = preg_replace('/\/\/+/', '/', $_SERVER["SCRIPT_FILENAME"]);
$install_with_root =  preg_replace('/\/\/+/', '/', ($_SERVER["DOCUMENT_ROOT"] . "/" . $installdir . "/"));

if (!array_key_exists(substr($server_script, strlen($install_with_root)), $allowedscripts)) {
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    if (DEBUG) {
        print("<br />'" . $server_script . "' not registered with db scripts. Compared with " . substr($server_script, strlen($install_with_root)));
        print("<br />Documentroot + installdir: " . $install_with_root);
    }
    exit;
}

//
//
// Functions                                                           ##
//
//

// *************************** basic db functions *************************** //

$db = new Database();
$properties = getproperties();

class SQLStatement
{
    protected $columns = array();

    protected $fields = array();
    protected $values = array(array());
    protected $datatypes = "";
    protected $special = "";

    private $order = array();
    private $number = 0;
    private $offset = 0;

    protected $query = "";

    protected $errors = array();
    private $error_limit_reached = false;

    // Dummy constructor
    protected function __construct()
    {
    }

    // Add an error message to be handled
    protected function register_error($error)
    {
        if ($this->error_limit_reached) {
            return;
        }
        if (count($this->errors) > 10) {
            $this->error_limit_reached = true;
        }
        array_push($this->errors, $error);
    }

    // Register an error for a PDOException and handle it
    protected function handle_pdo_exception($e)
    {
        self::register_error(
            "<strong>PDO error " . (int) $e->getCode() . ":</strong> "
            . $e->getMessage()
        );
        self::handle_errors();
    }


    //
    // security, use with all user input
    //
    static function setinteger($var)
    {
        if (!(@is_numeric($var) || @ctype_digit($var))) {
            return @settype($var, "int");
        } else {
            return $var;
        }
    }

    // Checks if the given list is a comma saparated string or an array of non-negative integers
    // Returns an array with keys ("errormessage", "content")
    static function prepare_integer_list($list)
    {
        $result = array("errormessage" => "", "content" => "");
        if (is_array($list)) {
            $array = $list;
        } else {
            $array = explode(',', $list);
        }

        for ($i=0; $i < count($array); $i++) {
            $array[$i] = trim($array[$i]);
            if (!(@is_numeric($array[$i]) || @ctype_digit($array[$i]))) {
                $result['errormessage'] .= ". Expected numbers separated by ','
                    but found '$array[$i]' in '$list'";
            } elseif($array[$i] < 0) {
                $result['errormessage'] .= ". Expected numbers >= 0 ','
                    but found '$array[$i]' in '$list'";
            }
        }
        $result['content'] = implode(",", $array);
        return $result;
    }

    // Check if table name is in the whitelist
    public function check_table_name($table)
    {
        global $legal_tables;
        if (!array_key_exists($table, $legal_tables)) {
            self::register_error("Illegal table name: $table");
            self::handle_errors();
        }
    }

    // Check if column names are in the whitelist
    protected function check_column_names($columns)
    {
        global $legal_columns;

        if (is_string($columns)) {
            $columns = array($columns);
        } elseif (!is_array($columns) && !SQLStatement::is_empty($columns)) {
            $this->register_error(
                "Constructing SQL statement:
                Columns $columns is not a string or array"
            );
        } else {
            foreach ($columns as $value) {
                if (!array_key_exists($value, $legal_columns)) {
                    self::register_error("Illegal column name: $value");
                    self::handle_errors();
                }
            }
        }
    }

    // Report error if we find an empty value
    protected function check_for_empty_values($values) {
        foreach ($values as $value) {
            if (SQLStatement::is_empty($value)) {
                $this->register_error(
                    "Constructing SQL statement: Empty value '$value'"
                );
            }
        }
    }

    // Validate fields, values and datatypes and then construct a "where" condition
    protected function construct_where_condition($allow_empty = false)
    {
        // Validate
        if (!SQLStatement::is_empty($this->fields)) {
            if (is_array($this->fields)) {
                if (!is_array($this->values[0])
                    || SQLStatement::is_empty($this->values[0])) {
                    $this->register_error(
                        "Constructing SQL statement: Fields without values"
                    );
                }
            } else {
                $this->register_error(
                    "Constructing SQL statement: Fields must be empty or an array"
                );
            }
        }

        if (!empty($this->values) && !empty($this->values[0])) {
            if (is_array($this->values[0])) {
                if (!$allow_empty) {
                    self::check_for_empty_values($this->values[0]);
                }
            } else {
                $this->register_error(
                    "Constructing SQL statement: Values must be empty or an array"
                );
            }
        }

        if (!empty($this->special)) {
            if (!is_string($this->special)) {
                $this->register_error(
                    "Constructing SQL statement:
                    Special condition must be empty or a string"
                );
            }
        }
        if (!empty($this->errors)) {
            return;
        }

        // Construct
        $i = 0;
        if (!empty($this->fields)) {
            for (; $i < count($this->fields); $i++) {
                if ($i === 0) {
                    $this->query .= " WHERE `" . $this->fields[$i] . "` = ?";
                } else {
                    $this->query .= " AND `" . $this->fields[$i] . "` = ?";
                }
            }
        }
        if (!empty($this->special)) {
            if ($i === 0) {
                $this->query .= " WHERE " . $this->special;
            } else {
                $this->query .= " AND " . $this->special;
            }
        }
    }

    // Adds an "in" condition
    // $column: the column key for restricting the condition
    // $range:  An array of integers
    function add_integer_range_condition($column, $range) {
        self::check_column_names($column);
        $count = count($range);
        $this->datatypes .= str_pad("", $count, 'i');
        $this->special .= " $column IN (" . implode(',', array_fill(0, $count, '?')) . ") ";
        $this->values[0] = array_merge($this->values[0], $range);
    }

    // Sets a sort order for the data returned
    // $order: array of (columname => direction) to order by
    //         direction is 'ASC' or 'DESC'
    function set_order($order)
    {
        self::check_column_names(array_keys($order));
        if (empty($this->errors)) {
            foreach ($order as $key => $value) {
                $this->order[]
                    = " `" . $key . "` "
                      . (mb_strtolower($value, 'UTF-8') == "desc" ? "DESC" : "ASC");
            }
        }
    }

    // Sets a limit for the number of rows returned
    function set_limit($number, $offset)
    {
        if ($number < 1) {
            if (DEBUG) {
                $this->register_error(
                    "Constructing SQL statement:
                    Limiting to $number < 1 does not make sense - pick a number > 0"
                );
            }
            $number = 0;
            $offset = 0;
        }
        if ($offset < 0) {
            if (DEBUG) {
                $this->register_error(
                    "Constructing SQL statement:
                    Limiting to $offset < 0 does not make sense - pick a number > 0"
                );
            }
            $offset = 0;
        }
        $this->number = SQLStatement::setinteger($number);
        $this->offset = SQLStatement::setinteger($offset);
    }

    private function order() {
        if (!empty($this->order)) {
            return " ORDER BY" . implode(',', $this->order);
        }
        return "";
    }

    private function limit() {
        if ($this->number > 0) {
            return " LIMIT " . $this->number . " OFFSET " . $this->offset;
        }
        return "";
    }

    // Prepares a mysqli query and returns the statement object
    private function prepare_query($query)
    {
        global $db;
        if (strpos($query, ';') !== false) {
            $this->register_error("Syntax error");
        }
        $statement = (!DEBUG || $db->quiet_mode) ?
            @$db->pdo->prepare($query) :
            $db->pdo->prepare($query);

        if (!$statement) {
            $this->register_error("Prepare failed: (" . @$db->pdo->errorCode . ") "
                . @$db->pdo->errorInfo[2] . "<br />"
            );
        }
        return $statement;
    }

    // Executes this query and returns the database statement
    function execute()
    {
        global $db;
        $this->construct_query();

        // Validate parameter count
        if (!(empty($this->datatypes) || is_string($this->datatypes))) {
            $this->register_error(
                "Executing SQL statement: Datatypes must be a string"
            );
            $this->handle_errors();
            return false;
        }

        $no_of_datatypes = strlen($this->datatypes);
        $questionmarks = count_chars($this->query, 0)[ord('?')];
        if ($questionmarks != $no_of_datatypes) {
            $this->register_error(
                "Parameter mismatch:
                $questionmarks occurrences of '?' in the query, but "
                . strlen($this->datatypes) . " datatypes specified"
            );
        }
        if ($this->handle_errors()) {
            return false;
        }

        $statement = $this->prepare_query($this->query . $this->order() . $this->limit());
        if ($this->handle_errors()) {
            return false;
        }

        if ($statement) {
            // Execute query without values
            if (empty($this->datatypes)) {
                if (!$statement->execute()) {
                    $this->register_error(
                        "Executing SQL statement returned ("
                        . $statement->errorCode . ") " . $statement->errorInfo[2]
                    );
                }
            } else {
                // Execute query with values
                $datatypes_array = array();
                for ($i = 0; $i < $no_of_datatypes; $i++) {
                    array_push($datatypes_array, $this->datatypes[$i] === 'i' ?
                        PDO::PARAM_INT :
                        PDO::PARAM_STR
                    );
                }

                for ($i = 0; $i < count($this->values); $i++) {
                    if (count($this->values[$i]) > $no_of_datatypes) {
                        $this->register_error(
                            "Executing SQL statement: You have only "
                            . $no_of_datatypes . " datatypes but "
                            . count($this->values) . " values"
                        );
                        continue;
                    }
                    for ($j = 0; $j < count($this->values[$i]); $j++) {
                        $statement->bindValue(
                            $j + 1,
                            $this->values[$i][$j],
                            $datatypes_array[$j]
                        );
                    }

                    if (!$statement->execute()) {
                        $this->register_error(
                            "Executing SQL statement returned ("
                            . $statement->errorCode . ") " . $statement->errorInfo[2]
                        );
                    }
                }
            }
        }
        if ($this->handle_errors()) {
            return false;
        }
        return $statement;
    }

    // Run the query and return whether it succeeded
    public function run()
    {
        try {
            self::execute();
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return empty($this->errors);
    }

    // Executes this query and returns a column of database values
    // Will only work with 1 column in select when this object was constructed
    function fetch_column()
    {
        try {
            $query_result = self::execute();
            if ($query_result) {
                return $query_result->fetchAll(PDO::FETCH_COLUMN);
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return array();
    }

    // Executes this query and returns two columns of database values
    // Will only work with 2 columns in select when this object was constructed
    function fetch_two_columns()
    {
        try {
            $query_result = self::execute();
            if ($query_result) {
                return $query_result->fetchAll(PDO::FETCH_KEY_PAIR);
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return array();
    }

    // Get an assiciative array for 1 row
    function fetch_row()
    {
        try {
            $query_result = self::execute();
            if ($query_result) {
                return $query_result->fetch(PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return array();
    }

    // Returns a mapping from keys to asociative arrays of field, value pairs
    // The first column from the constructor acts as keys for the result array
    function fetch_many_rows()
    {
        try {
            $query_result = $this->execute();
            if ($query_result) {
                return $query_result->fetchAll(PDO::FETCH_UNIQUE);
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return array();
    }

    // Fetch all values as associative array
    function fetch_all()
    {
        try {
            $query_result = $this->execute();
            if ($query_result) {
                return $query_result->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return array();
    }

    // Return the first value fetched by the query
    function fetch_value()
    {
        try {
            $query_result = $this->execute();
            if ($query_result) {
                return $query_result->fetchColumn();
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return false;
    }

    // Returns an error report constructed from the reported errors and adds backtrace info.
    // If $db->quiet_mode is off, prints it too.
    private function handle_errors()
    {
        global $db;

        if (!empty($this->errors)) {
            if (DEBUG) {
                $db->error_report = "<h4>Errors:</h4>\n";
                $db->error_report .= "<ul>\n";
                foreach ($this->errors as $error) {
                    $db->error_report .= "<li>$error</li>\n";
                }
                $db->error_report .= "</ul>\n";
                $db->error_report .= "<strong>Query:</strong> " . $this->query;
                $db->error_report .= "<br />\n";
                if (is_array($this->values)
                    && !SQLStatement::is_empty($this->values[0])) {

                    $db->error_report .= "<h4>Values:</h4>\n";
                    $db->error_report .= "<ul>\n";

                    for ($i = 0; $i < min(10, count($this->values)); $i++) {
                        $db->error_report .= "<li>\n";

                        if (is_array($this->values[$i])) {
                            foreach ($this->values[$i] as $value) {
                                $db->error_report .= " $value";
                            }
                            $db->error_report .= " (". count($this->values[$i]) . ")";
                        } else {
                            $db->error_report .= " " . $this->values[0]
                                . " (Not an array)";
                        }
                        $db->error_report .= "</li>\n";
                    }
                    $db->error_report .= "</ul>\n";
                } else {
                    $db->error_report .= "<h4>No values</h4>\n";
                }
                $db->error_report .= "<strong>Datatypes:</strong> '$this->datatypes'";
                if (is_string($this->datatypes)) {
                    $db->error_report .= " (". strlen($this->datatypes) . ")";
                }
                $db->error_report .= "<br />\n";
                if (!empty($this->special)) {
                    $db->error_report .= "<strong>Special Condition:</strong> '$this->special'<br />\n";
                }
                $db->error_report .= "<strong>Number:</strong> $this->number<br /><strong>Offset: </strong> $this->offset<br />\n";

                $backtrace = debug_backtrace();
                if (!empty($backtrace)) {
                    $db->error_report .= "<h4>Backtrace:</h4>\n";
                    $db->error_report .= "<ul>\n";
                    foreach ($backtrace as $entry) {
                        $db->error_report .= "<li>\n";
                        $db->error_report .= "<strong>" . $entry['file'] . ":"
                            . $entry['line'] . "</strong> @ ";

                        if (isset($entry['object']) && !empty($entry['object'])) {
                            $db->error_report .= get_class($entry['object']) . ":";
                        }
                        if (isset($entry['function']) && !empty($entry['function'])) {
                            $db->error_report .= "<em>" . $entry['function'] . "</em>";
                        }
                        if (isset($entry['class']) && !empty($entry['class'])) {
                            $db->error_report .= " (" . $entry['class'] . ")";
                        }
                        $db->error_report .= "</li>\n";
                    }
                    $db->error_report .= "</ul>\n";
                }
            } else {
                $db->error_report
                    .= "<strong>Error getting data from database.</strong>";
            }
            if (!$db->quiet_mode) {
                print($db->error_report);
            }
            return true;
        }
        return false;
    }

    // empty() with handling numeric to avoid reporting "0" as empty
    protected static function is_empty($value)
    {
        return (empty($value) && !is_numeric($value));
    }
}

// Handle any complex SQL statement
class RawSQLStatement extends SQLStatement
{

    // Constructs an arbitary SQL statament
    // $statement: an SQL statement with variables repaced by ?
    // $values:    values replacing ?
    // $datatypes: string of data types for values, e.g. 'is'
    //             needs to match the values
    function __construct($statement, $values = array(), $datatypes = "")
    {
        // TODO see what we can sanitize
        // Set parameters
        $this->query = $statement;
        $this->datatypes = $datatypes;
        $this->values[0] = $values;

        // Verify parameters
        if (!is_string($statement)) {
            $this->register_error(
                "Constructing SQL statement: Statement must be a string"
            );
        }

        if (is_array($values)) {
            self::check_for_empty_values($values);
        } else {
            $this->register_error(
                "Constructing SQL statement: Values must be an array"
            );
        }
    }

    protected function construct_query()
    {
        // Do nothing
    }
}

// Insert values into table
class SQLInsertStatement extends SQLStatement
{
    private $table;

    // Inserts a row into a table
    // $table:     name of the table where the elements get inserted
    // $columns:   the colum names for the insert statement
    // $values:    values to be inserted
    // $datatypes: string of data types for values, e.g. 'isi'
    //             needs to match the values
    function __construct($table, $columns, $values, $datatypes)
    {
        // Set parameters
        $this->table = $table;
        $this->datatypes = $datatypes;
        $this->fields = $columns;
        $this->values[0] = $values;

        self::check_table_name($table);
        self::check_column_names($columns);

        if (is_array($values)) {
            self::check_for_empty_values($values);
            if (is_array($columns)) {
                if (count($values) != count($columns)) {
                    $this->register_error(
                        "Constructing SQL statement: You have " . count($columns)
                        . " columns but " . count($values) . " values"
                    );
                }
            } else {
                $this->register_error(
                    "Constructing SQL statement: Columns must be an array"
                );
            }
        } else {
            $this->register_error(
                "Constructing SQL statement: Values must be an array"
            );
        }
    }

    protected function construct_query()
    {
        if (!empty($this->errors)) {
            return;
        }
        $this->query = "INSERT INTO $this->table (";
        $this->query .= implode(', ', $this->fields);
        $this->query .= ") values (";
        $this->query .= implode(', ', array_fill(0, count($this->values[0]), '?'));
        $this->query .= ")";
    }

    // Run the query and get the generated ID
    public function insert()
    {
        global $db;
        try {
            $query_result = self::execute();
            if ($query_result) {
                return $db->pdo->lastInsertId();
            }
        } catch (\PDOException $e) {
            self::handle_pdo_exception($e);
        }
        return false;
    }
}

// Update database values
class SQLUpdateStatement extends SQLStatement
{
    private $table;

    // Updates values in a table
    // $table:     name of the table where the elements get updated
    // $columns:   the colums to be updated
    // $fields:    keys in WHERE "key = value" conditions
    // $values:    values in WHERE "key = value" conditions followed by values for columns
    //             if you wish to update multiple records, use an empty array here
    //             and call the set_values() function instead
    // $datatypes: string of data types for values, e.g. 'isi'
    //             needs to match the values
    function __construct($table, $columns, $fields, $values, $datatypes)
    {
        $this->table = $table;
        $this->datatypes = $datatypes;
        $this->fields = $fields;
        $this->values[0] = $values;

        self::check_table_name($table);
        self::check_column_names($fields);

        // Check and set columns
        self::check_column_names($columns);
        if (is_array($columns)) {
            $this->columns = $columns;
        } elseif (is_string($columns) || SQLStatement::is_empty($columns)) {
            $this->columns = array($columns);
        }
    }

    // An array of records to update, containing arrays of values for each record.
    // Those inner arrays have the same format as the $values array in the constructor
    function set_values($values)
    {
        if (empty($values)) {
            $this->register_error("Tried to add empty values");
        }
        $this->values = $values;
    }

    protected function construct_query()
    {
        $columns_to_values = array();
        foreach ($this->columns as $column) {
            array_push($columns_to_values, "$column = ?");
        }

        $this->query = "UPDATE $this->table SET ";
        $this->query .= implode(', ', $columns_to_values);

        $this->construct_where_condition(true);
    }
}

// Delete database values
class SQLDeleteStatement extends SQLStatement
{
    private $table;

    // Deletes values in a table
    // $table:             name of the table where the elements get deleted
    // $fields:            keys in WHERE "key = value" conditions
    // $values:            values in WHERE "key = value" conditions
    // $datatypes:         string of data types for values, e.g. 'isi'
    //                     needs to match the values
    // $special_condition: use this to add any WHERE condition that doesn't fit the "key = value" pattern
    //                     values should show up as "?" and the real values added to $values and their datatypes to $datatypes
    function __construct($table, $fields, $values, $datatypes, $special_condition = "")
    {
        $this->table = $table;
        $this->datatypes = $datatypes;
        $this->fields = $fields;
        $this->values[0] = $values;
        $this->special = $special_condition;

        self::check_table_name($table);
        self::check_column_names($fields);

        if (SQLStatement::is_empty($this->values)) {
            $this->register_error(
                "Constructing SQL statement: Values are empty"
            );
        }

        if (SQLStatement::is_empty($this->fields)
            && SQLStatement::is_empty($this->special)) {
            $this->register_error(
                "Constructing SQL statement:
                Fields and special condition are both empty"
            );
        }
    }

    protected function construct_query()
    {
        $this->query = "DELETE FROM $this->table";
        $this->construct_where_condition();
    }
}

// Select query with left join
class SQLJoinStatement extends SQLStatement
{
    private $table1;
    private $id1;
    private $table2;
    private $id2;

    // Constructs a left join SQL statament
    // $table1:            Left table
    // $id1:               Join ID for left table
    // $table2:            Right table
    // $id2:               Join ID for right table
    // $fields:            keys in WHERE "key = value" conditions
    // $values:            values in WHERE "key = value" conditions
    // $datatypes:         string of data types for values in WHERE conditions, e.g. 'isi'
    //                     needs to match the values and anything added in special_condition
    function __construct($table1, $id1, $table2, $id2, $fields = array(), $values = array(),
        $datatypes = "") {
        // Verify parameters
        self::check_table_name($table1);
        self::check_table_name($table2);
        self::check_column_names(array($id1, $id2));
        self::check_column_names($fields);

        // Set parameters
        $this->table1 = $table1;
        $this->id1 = $id1;
        $this->table2 = $table2;
        $this->id2 = $id2;
        $this->fields = $fields;
        $this->values[0] = $values;
        $this->datatypes = $datatypes;
    }

    protected function construct_query()
    {
        $this->query = "SELECT * FROM " . $this->table1 . " LEFT JOIN " . $this->table2 . " ON "
            . $this->table1 . "." . $this->id1 . " = "
            . $this->table2 . "." . $this->id2;

        $this->construct_where_condition();
    }
}

// Object for building SQL queries from input and fetching the data from the database
class SQLSelectStatement extends SQLStatement
{
    private $table;

    private $join = array();

    private $distinct = false;
    private $operator = "";

    // Constructs an SQL statament
    // $table:             database table name
    // $columns:           array of column names to fetch, or a string with 1 column name to fetch
    //                     first column acts as keys for any result arrays
    // $fields:            keys in WHERE "key = value" conditions
    // $values:            values in WHERE "key = value" conditions
    // $datatypes:         string of data types for values in WHERE conditions, e.g. 'isi'
    //                     needs to match the values and anything added in special_condition
    // $special_condition: use this to add any WHERE condition that doesn't fit the "key = value" pattern
    //                     values should show up as "?" and the real values added to $values and their datatypes to $datatypes
    function __construct($table, $columns, $fields = array(), $values = array(),
        $datatypes = "", $special_condition = ""
    ) {
        $this->table = $table;
        $this->columns = $columns;
        $this->fields = $fields;
        $this->values[0] = $values;
        $this->datatypes = $datatypes;
        $this->special = $special_condition;

        self::check_table_name($table);
        self::check_column_names($fields);
        self::check_column_names($columns);
    }

    // Replace "SELECT" with "SELECT DISTINCT" when building the query string
    function set_distinct()
    {
        $this->distinct = true;
    }

    // Select with joining 2 tables. Left table is the one from the constructor.
    // $id1:               Join ID for left table
    // $table2:            Right table
    // $id2:               Join ID for right table
    //                     needs to match the values and anything added in special_condition
    // TODO replace SQLJoinStatement with this
    function set_join($id1, $table2, $id2) {
        // Verify parameters
        self::check_table_name($table2);
        self::check_column_names(array($id1, $id2));

        // Set parameters
        $this->join = array(
            'id1' => $id1,
            'table2' => $table2,
            'id2' => $id2,
        );
    }

    // SELECT with count, min, max or sum
    function set_operator($operator)
    {
        $allowed_operators = array('min' => 1, 'max' => 1, 'count' => 1, 'sum' => 1);
        if (!array_key_exists($operator, $allowed_operators)) {
            $this->register_error(
                "Constructing SQL statement:
                Illeagal operator '$operator' - allowed operators are: "
                . implode(', ', array_keys($allowed_operators))
            );
        }
        $this->operator = $operator;
    }

    // Returns an SQL statement
    protected function construct_query()
    {
        $columns = '';
        if (is_array($this->columns)) {
            $columns = '`' . implode("`, `", $this->columns) . '`';
        } elseif (is_string($this->columns)) {
            $columns = $this->columns === '*' ?
                $this->columns :
                '`' . $this->columns . '`';
        }

        $this->query = $this->distinct ? "SELECT DISTINCT "  : "SELECT ";

        $this->query .=
            empty($this->operator) ?
            $columns :
            $this->operator . "(" . $columns . ")";

        $this->query .= " FROM `" . $this->table . "`";

        if (!empty($this->join)) {
            $this->query .= " LEFT JOIN " . $this->join['table2'] . " ON "
                . $this->table . "." . $this->join['id1'] . " = "
                . $this->join['table2'] . "." . $this->join['id2'];
        }

        $this->construct_where_condition();
    }
}

/**
 * Database object to handle the connection and store error reports.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */
class Database
{
    var $error_report = "";
    var $quiet_mode = false;
    var $pdo;

    /**
     * Opens a database connection. Call this only once at beginning of script.
     */
    function __construct()
    {
        global $dbname,$dbhost,$dbuser,$dbpasswd;

        // TODO $charset = 'utf8mb4'; would be nice
        $dsn = 'mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8';

        try {
            $options = array(
                PDO::ATTR_ERRMODE => ((DEBUG && !$this->quiet_mode) ?
                    PDO::ERRMODE_EXCEPTION :
                    PDO::ERRMODE_SILENT),

                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
            );
            if (DEBUG && !$this->quiet_mode) {
                $this->pdo = new PDO($dsn, $dbuser, $dbpasswd, $options);
            } else {
                $this->pdo = @new PDO($dsn, $dbuser, $dbpasswd, $options);
            }
        } catch (\PDOException $e) {
            echo "Can't connect to database. Please try again later." . "<br />";
            if (DEBUG) {
                print(
                    "<strong>PDO error " . (int) $e->getCode() . ":</strong> "
                    . $e->getMessage() .  "<br />"
                );
                debug_print_backtrace();
            }
            exit();
        }
    }
}

// *************************** properties *********************************** //

//
// returns an associative array of properties
//
function getproperties()
{
    $sql = new SQLSelectStatement(SITEPROPERTIES_TABLE, '*');
    return $sql->fetch_two_columns();
}

//
//
//
function getproperty($propertyname)
{
    global $properties;
    return $properties[$propertyname];
}


//
// updates an associative array of properties
//
function updateproperties($table, $newproperties, $max_value_length = 0)
{
    global $properties;
    $result = "";

    // Bring into shape for the database call
    $values = array();
    foreach ($newproperties as $key => $value) {
        if ($max_value_length > 0 && strlen($value) > $max_value_length) {
            // Restrict to e.g. 255 characters
            $result .= " Value '$value' is ". strlen($value)
                . " characters long, but only $max_value_length characters can fit. "
                . "It has been cut off.";
            $newproperties[$key] = substr($value, 0, $max_value_length);
        }
        array_push($values, array($value, $key));
    }

    // Write
    $sql = new SQLUpdateStatement(
        $table,
        array('property_value'), array('property_name'),
        array(), 'ss'
    );
    $sql->set_values($values);

    if (!$sql->run()) {
        $result = "Failed to save properties" . $result;
    }
    return $result;
}
?>
