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
    "admin/activate.php",
    "admin/admin.php",
    "admin/edit/articleedit.php",
    "admin/edit/galleryedit.php",
    "admin/edit/linklistedit.php",
    "admin/edit/menuedit.php",
    "admin/edit/newsedit.php",
    "admin/edit/pageintrosettingsedit.php",
    "admin/editcategories.php",
    "admin/editimagelist.php",
    "admin/includes/ajax/articles/addcategories.php",
    "admin/includes/ajax/articles/removecategories.php",
    "admin/includes/ajax/articles/savesectiontitle.php",
    "admin/includes/ajax/articles/savesource.php",
    "admin/includes/ajax/articles/updatecategories.php",
    "admin/includes/ajax/articles/updatesectiontitle.php",
    "admin/includes/ajax/editor/collapseeditor.php",
    "admin/includes/ajax/editor/editorcontentssavedialog.php",
    "admin/includes/ajax/editor/expandeditor.php",
    "admin/includes/ajax/editor/formatpreviewtext.php",
    "admin/includes/ajax/editor/getserverprotocol.php",
    "admin/includes/ajax/editor/gettextfromdatabase.php",
    "admin/includes/ajax/editor/savetext.php",
    "admin/includes/ajax/galleries/saveimage.php",
    "admin/includes/ajax/galleries/updateimage.php",
    "admin/includes/ajax/imageeditor/saveimagefilename.php",
    "admin/includes/ajax/imageeditor/saveimagealignment.php",
    "admin/includes/ajax/imageeditor/saveimagesize.php",
    "admin/includes/ajax/imageeditor/showimagealignment.php",
    "admin/includes/ajax/imageeditor/showimagesize.php",
    "admin/includes/ajax/imageeditor/updateimage.php",
    "admin/includes/ajax/imagelist/addcategories.php",
    "admin/includes/ajax/imagelist/getimageusage.php",
    "admin/includes/ajax/imagelist/removecategories.php",
    "admin/includes/ajax/imagelist/savedescription.php",
    "admin/includes/ajax/imagelist/updatecategories.php",
    "admin/includes/ajax/imagelist/updateimage.php",
    "admin/includes/ajax/linklists/",
    "admin/includes/ajax/linklists/",
    "admin/includes/ajax/linklists/savelinkproperties.php",
    "admin/includes/ajax/linklists/updatelinktitle.php",
    "admin/includes/ajax/menus/movepage.php",
    "admin/includes/ajax/menus/saveoptions.php",
    "admin/includes/ajax/menus/updatesubpages.php",
    "admin/includes/ajax/news/addcategories.php",
    "admin/includes/ajax/news/publish.php",
    "admin/includes/ajax/news/removecategories.php",
    "admin/includes/ajax/news/savedate.php",
    "admin/includes/ajax/news/savepermissions.php",
    "admin/includes/ajax/news/savesectiontitle.php",
    "admin/includes/ajax/news/savesource.php",
    "admin/includes/ajax/news/savetitle.php",
    "admin/includes/ajax/news/unpublish.php",
    "admin/includes/ajax/news/updatecategories.php",
    "admin/includes/ajax/news/updatedate.php",
    "admin/includes/ajax/news/updatesectiontitle.php",
    "admin/includes/ajax/news/updatetitle.php",
    "admin/includes/pagelist.php",
    "admin/includes/preview.php",
    "admin/login.php",
    "admin/pagedelete.php",
    "admin/pagedisplay.php",
    "admin/pageedit.php",
    "admin/pagenew.php",
    "admin/profile.php",
    "admin/register.php",
    "admin/showimage.php",
    "contact.php",
    "guestbook.php",
    "index.php",
    "login.php",
    "rss.php",
    "showimage.php",
    "stuth/geamannan/bs/index.php",
    "stuth/geamannan/crochadair/getword.php",
    "stuth/geamannan/crochadair/index.php",
    "stuth/geamannan/leacan/index.php",
    "stuth/geamannan/leumadair/index.php",
    "stuth/geamannan/longan/index.php",
    "stuth/geamannan/matamataigs/index.php",
    "stuth/geamannan/tetris/highscore.php",
    "stuth/geamannan/tetris/index.php",
    "stuth/geamannan/tt/getpuzzle.php"
);

$server_script = preg_replace('/\/\/+/', '/', $_SERVER["SCRIPT_FILENAME"]);
$install_with_root =  preg_replace('/\/\/+/', '/', ($_SERVER["DOCUMENT_ROOT"] . "/" . $installdir . "/"));

if(!in_array(substr($server_script, strlen($install_with_root)), $allowedscripts)) { die;
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

    private $number = 0;
    private $offset = 0;

    protected $query = "";

    protected $errors = array();
    private $error_limit_reached = false;

    // Dummy constructor
    protected function SQLStatement()
    {
    }

    // Add an error message to be handled
    protected function register_error($error)
    {
        if ($this->error_limit_reached) { return;
        }
        if (count($this->errors) > 10) {
            $this->error_limit_reached = true;
        }
        array_push($this->errors, $error);
    }


    //
    // security, use with all user input
    //
    static function setinteger($var)
    {
        if(!(@is_numeric($var) || @ctype_digit($var))) { return @settype($var, "int");
        } else { return $var;
        }
    }

    //
    // security, use with all user input
    // also handles UTF-8 encoding!
    //
    static function setstring($var)
    {
        global $db;
        $result = @$db->db->real_escape_string($var);
        return utf8_decode($result);
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
                $result['errormessage'] .= ". Expected numbers separated by ',' found '$array[$i]' in '$list'";
            } elseif($array[$i] < 0) {
                $result['errormessage'] .= ". Expected numbers >= 0 ',' found '$array[$i]' in '$list'";
            }
        }
        $result['content'] = implode(",", $array);
        return $result;
    }

    // Force int or string on all values
    private function sanitize_values()
    {
        global $db;

        if (!empty($this->values[0])) {
            // Check datatypes
            $datatype_array = str_split($this->datatypes);
            foreach ($datatype_array as $value) {
                if ($value !== 'i' && $value !== 's') {
                    $this->register_error(
                        "Constructing SQL statement:
						Datatypes must be s or i - found '$value' in '$this->datatypes'"
                    );
                }
                return;
            }

            // Ensure datatypes on values
            $columns = count($this->values[0]);
            $rows = count($this->values);

            for ($i = 0; $i < $rows; $i++) {
                for ($j = 0; $j < $columns; $j++) {
                    $this->values[$i][$j] = $datatype_array[$j] === 'i' ?
                    SQLStatement::setinteger($this->values[$i][$j]) :
                    SQLStatement::setstring($this->values[$i][$j]);
                }
            }
        }
    }

    // Validate fields, values and datatypes and then construct a "where" condition
    protected function construct_where_condition($allow_empty = false)
    {
        // Validate
        if (!SQLStatement::is_empty($this->fields)) {
            if (is_array($this->fields)) {
                if (!is_array($this->values[0]) || SQLStatement::is_empty($this->values[0])) {
                    $this->register_error(
                        "Constructing SQL statement:
						Fields without values"
                    );
                }
            } else {
                $this->register_error(
                    "Constructing SQL statement:
					Fields must be empty or an array"
                );
            }
        }

        if (!SQLStatement::is_empty($this->datatypes)) {
            if (!is_string($this->datatypes)) {
                $this->register_error(
                    "Constructing SQL statement:
					Datatypes must be empty or a string"
                );
            }
        }

        if (!empty($this->values) && !empty($this->values[0])) {

            if (is_array($this->values[0])) {
                if (!$allow_empty) {
                    foreach ($this->values[0] as $value) {
                        if (SQLStatement::is_empty($value)) {
                            $this->register_error(
                                "Constructing SQL statement:
								Empty value '$value'"
                            );
                        }
                    }
                }

                if (count($this->fields) > count($this->values[0])) {
                    $this->register_error(
                        "Constructing SQL statement:
						You have " . count($this->fields) . " condition fields but only " . count($this->values[0]) . " condition values"
                    );
                }
            } else {
                $this->register_error(
                    "Constructing SQL statement:
					Values must be empty or an array"
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
        if (!empty($this->errors)) { return;
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

    // Sets a limit for the number of rows returned
    function set_limit($number, $offset)
    {
        global $db;
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

    private function limit()
    {
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
        $statement = (!DEBUG || $db->quiet_mode) ? @$db->db->prepare($query) : $db->db->prepare($query);
        if (!$statement) {
            $this->register_error("Prepare failed: (" . @$db->db->errno . ") " . @$db->db->error . "<br />");
        }
        return $statement;
    }


    // Executes this query and returns the database statement
    function execute()
    {
        global $db;
        $this->construct_query();
        $this->sanitize_values();

        $questionmarks = count_chars($this->query, 0)[ord('?')];
        if ($questionmarks != strlen($this->datatypes)) {
            $this->register_error(
                "Parameter mismatch:
				$questionmarks occurrences of '?' in the query, but "
                . strlen($this->datatypes) . " datatypes specified"
            );
        }
        if ($this->handle_errors()) { return false;
        }

        $statement = $this->prepare_query($this->query . $this->limit());
        if ($this->handle_errors()) { return false;
        }

        if ($statement) {
            if (empty($this->datatypes)) {
                if (!$statement->execute()) {
                    $this->register_error(
                        "Executing SQL statement returned ("
                        . $statement->errno . ") " . $statement->error
                    );
                }
            } else {
                $reflection_array = array($this->datatypes);
                // Iterate value sets
                for ($i = 0; $i < count($this->values); $i++) {
                    // Collect values for 1 execution and run
                    for ($j = 0; $j < count($this->values[$i]); $j++) {
                        $reflection_array[$j+1] = &$this->values[$i][$j];
                    }
                    $reflection = new ReflectionClass('mysqli_stmt');
                    $method = $reflection->getMethod("bind_param");
                    if ($db->quiet_mode) {
                        if (!@$method->invokeArgs($statement, $reflection_array)) {
                            $this->register_error("Failed to bind parameters");
                        }
                    } else {
                        if (!$method->invokeArgs($statement, $reflection_array)) {
                            $this->register_error("Failed to bind parameters");
                        }
                    }
                    if (!$statement->execute()) {
                        $this->register_error(
                            "Executing SQL statement returned ("
                            . $statement->errno . ") " . $statement->error
                        );
                    }
                }
            }
        }

        if ($this->handle_errors()) { return false;
        }
        return $statement;
    }

    // Run the query and return whether it succeeded
    public function run()
    {
        $this->execute();
        return empty($this->errors);
    }

    // Executes this query and returns a column of database values
    // Will only work with 1 column in select when this object was constructed
    function fetch_column()
    {
        $result = array();
        $query_result = $this->execute();
        if ($query_result) {
            $query_result = $query_result->get_result();
            while ($row = mysqli_fetch_array($query_result, MYSQLI_NUM)) {
                array_push($result, $row[0]);
            }
        }
        return $result;
    }

    // Executes this query and returns two columns of database values
    // Will only work with 2 columns in select when this object was constructed
    function fetch_two_columns()
    {
        $result = array();
        $query_result = $this->execute();
        if ($query_result) {
            $query_result = $query_result->get_result();
            while ($row = $query_result->fetch_row()) {
                $result[$row[0]] = $row[1];
            }
        }
        return $result;
    }

    // Get an assiciative array for 1 row
    function fetch_row()
    {
        $query_result = $this->execute();
        if ($query_result) {
            return $query_result->get_result()->fetch_assoc();
        }
        return array();
    }

    // Returns a mapping from keys to asociative arrays of field, value pairs
    // The first column from the constructor acts as keys for the result array
    function fetch_many_rows()
    {
        $result = array();
        $query_result = $this->execute();
        if (is_object($query_result)) {
            $query_result = $query_result->get_result();
            while ($row = mysqli_fetch_array($query_result, MYSQLI_NUM)) {
                $row_array = array();
                // The first column will be the key, so we skip it
                for ($i = 1; $i < count($this->columns); $i++) {
                    $row_array[$this->columns[$i]] = $row[$i];
                }
                // Use first column as key
                $result[$row[0]] = $row_array;
            }
        }
        return $result;
    }

    // Fetch all values as associative array
    function fetch_all()
    {
        $result = array();
        $query_result = $this->execute();
        if (is_object($query_result)) {
            $result = $query_result->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return $result;
    }

    // Return the first value fetched by the query
    function fetch_value()
    {
        $query_result = $this->execute();
        if ($query_result) {
            $query_result = $query_result->get_result();
            $row = mysqli_fetch_array($query_result, MYSQLI_NUM);
            return $row[0];
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
                $db->error_report .= "<strong>Query:</strong> " . $this->query . "<br />\n";
                if (is_array($this->values) && !SQLStatement::is_empty($this->values[0])) {
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
                            $db->error_report .= " " . $this->values[0] . " (Not an array)";
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
                        $db->error_report .= "<strong>" . $entry['file'] . ":" . $entry['line'] . "</strong> @ ";
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
                $db->error_report .= "<strong>Error getting data from database.</strong>";
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
    function RawSQLStatement($statement, $values = array(), $datatypes = "")
    {
        // Set parameters
        $this->query = $statement;
        $this->datatypes = $datatypes;
        $this->values[0] = $values;

        // Verify parameters
        if (!is_string($statement)) {
            $this->register_error(
                "Constructing SQL statement:
				Statement must be a string"
            );
        }

        if (!is_string($datatypes)) {
            $this->register_error(
                "Constructing SQL statement:
				Datatypes must be a string"
            );
        }

        if (is_array($values)) {
            foreach ($values as $value) {
                if (SQLStatement::is_empty($value)) {
                    $this->register_error(
                        "Constructing SQL statement:
						Empty value '$value'"
                    );
                }
            }
        } else {
            $this->register_error(
                "Constructing SQL statement:
				Values must be an array"
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
    function SQLInsertStatement($table, $columns, $values, $datatypes)
    {
        // Set parameters
        $this->table = $table;
        $this->datatypes = $datatypes;
        $this->fields = $columns;
        $this->values[0] = $values;

        // Check table
        if (!is_string($table)) {
            $this->register_error(
                "Constructing SQL statement:
				Table $table is not a string"
            );
        }

        // Verify parameters
        if (!is_string($datatypes)) {
            $this->register_error(
                "Constructing SQL statement:
				Datatypes must be an array"
            );
        }

        if (is_array($values)) {
            foreach ($values as $value) {
                if (SQLStatement::is_empty($value)) {
                    $this->register_error(
                        "Constructing SQL statement:
						Empty value '$value'"
                    );
                }
            }
            if (count($values) != strlen($datatypes)) {
                $this->register_error(
                    "Constructing SQL statement:
					You have " . strlen($datatypes) . " datatypes but " . count($values) . " values"
                );
            }
            if (is_array($columns)) {
                if (count($values) != count($columns)) {
                    $this->register_error(
                        "Constructing SQL statement:
						You have " . count($columns) . " columns but " . count($values) . " values"
                    );
                }
            } else {
                $this->register_error(
                    "Constructing SQL statement:
					Columns must be an array"
                );
            }
        } else {
            $this->register_error(
                "Constructing SQL statement:
				Values must be an array"
            );
        }
    }

    protected function construct_query()
    {
        if (!empty($this->errors)) { return;
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
        $result = false;
        $query_result = $this->execute();
        if ($query_result) {
            return $query_result->insert_id;
        }
        return $result;
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
    function SQLUpdateStatement($table, $columns, $fields, $values, $datatypes)
    {
        $this->table = $table;
        $this->datatypes = $datatypes;
        $this->fields = $fields;
        $this->values[0] = $values;

        // Check and set columns
        if (is_array($columns)) {
            $this->columns = $columns;
        } elseif (is_string($columns) || SQLStatement::is_empty($columns)) {
            $this->columns = array($columns);
        } else {
            $this->register_error(
                "Constructing SQL statement:
				Columns $columns is not a string or array"
            );
        }

        // Check table
        if (!is_string($table)) {
            $this->register_error(
                "Constructing SQL statement:
				Table $table is not a string"
            );
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
    function SQLDeleteStatement($table, $fields, $values, $datatypes, $special_condition = "")
    {
        $this->table = $table;
        $this->datatypes = $datatypes;
        $this->fields = $fields;
        $this->values[0] = $values;
        $this->special = $special_condition;

        // Check table
        if (!is_string($table)) {
            $this->register_error(
                "Constructing SQL statement:
				Table $table is not a string"
            );
        }

        if (SQLStatement::is_empty($this->values)) {
            $this->register_error(
                "Constructing SQL statement:
				Values are empty"
            );
        }

        if (SQLStatement::is_empty($this->fields) && SQLStatement::is_empty($this->special)) {
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

// Object for building SQL queries from input and fetching the data from the database
class SQLSelectStatement extends SQLStatement
{
    private $table;

    private $order = array();

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
    function SQLSelectStatement($table, $columns, $fields = array(), $values = array(), $datatypes = "", $special_condition = "")
    {
        $this->table = $table;
        $this->columns = $columns;
        $this->fields = $fields;
        $this->values[0] = $values;
        $this->datatypes = $datatypes;
        $this->special = $special_condition;

        // Check table
        if (is_string($table)) {
        } else {
            $this->register_error(
                "Constructing SQL statement:
				Table $table is not a string"
            );
        }

        // Check columns
        if (is_string($columns) || is_array($columns)) {
        } elseif (!SQLStatement::is_empty($columns)) {
            $this->register_error(
                "Constructing SQL statement:
				Columns $columns is not a string or array"
            );
        }
    }

    // Sets a sort order for the data returned
    // $order: array of (columname => direction) to order by
    //         direction is 'ASC' or 'DESC'
    function set_order($order)
    {
        global $db;
        foreach ($order as $key => $value) {
            $this->order[] =
            " `" . SQLStatement::setstring($key) . "` "
            . (mb_strtolower($value) == "desc" ? "DESC" : "ASC");
        }
    }

    // Replace "SELECT" with "SELECT DISTINCT" when building the query string
    function set_distinct()
    {
        $this->distinct = true;
    }

    // SELECT with count, min, max or sum
    function set_operator($operator)
    {
        $allowed_operators = array('min', 'max', 'count', 'sum');
        if(!in_array($operator, $allowed_operators)) {
            $this->register_error(
                "Constructing SQL statement:
				Illeagal operator '$operator' - allowed operators are: "
                . implode(', ', $allowed_operators)
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
            $columns = $this->columns;
            if ($columns !== '*') {
                $columns = '`' . $columns . '`';
            }
        }

        $this->query = $this->distinct ? "SELECT DISTINCT "  : "SELECT ";
        $this->query .=
        empty($this->operator) ?
        $columns :
        $this->operator . "(" . $columns . ")";

        $this->query .= " FROM `" . $this->table . "`";

        $this->construct_where_condition();

        if (!empty($this->order)) {
            $this->query .= " ORDER BY" . implode(',', $this->order);
        }
    }
}

/*
 * Use Database object to limit number of connections
 */
class Database
{
    var $db;
    var $error_report = "";
    var $quiet_mode = false;

    /*
    * open DB at beginning of script
    */
    function __construct()
    {
        global $dbname,$dbhost,$dbuser,$dbpasswd;

        if (DEBUG && !$this->quiet_mode) {
            $this->db=new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
        } else {
            $this->db=@new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
        }

        if (!$this->db && !$this->quiet_mode) {
            echo "Can't connect to database. Please try again later." . "<br />";
            if(DEBUG) {
                echo "Debugging errno: " . mysqli_connect_errno() . "<br />";
                echo "Debugging error: " . mysqli_connect_error() . "<br />";
                debug_print_backtrace();
            }
            exit();
        }
    }

    /*
    * close DB at end of script
    */
    function __destruct()
    {
        @mysqli_close($db);
    }
}

// *************************** properties *********************************** //

//
// returns an associative array of properties
//
function getproperties()
{
    $sql = new SQLSelectStatement(SITEPROPERTIES_TABLE, array('property_name', 'property_value'));
    $result = $sql->fetch_two_columns();
    if (empty($result)) { exit(1);
    }
    return $result;
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
    foreach($newproperties as $key => $value) {
        if ($max_value_length > 0 && strlen($value) > $max_value_length) {
            // Restrict to e.g. 255 characters
            $result .= " Value '$value' is ". strlen($value) . " characters long, but only $max_value_length characters can fit. It has been cut off.";
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
