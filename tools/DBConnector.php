<?php

// Include configuration constants and function definitions
include_once dirname(__FILE__) . '/../config/defines.php'; // Including the defines file, which contains database constants
include_once 'functions.php'; // Including additional functions, if any

/**
 * Class DBConnector
 * 
 * This class is responsible for managing the database connection and executing SQL queries.
 */
class DBConnector {

    /**
     * Constructor method.
     * 
     * Currently, the constructor does not take any parameters or perform any actions.
     * In the future, it could be modified to accept parameters such as username or password.
     */
    public function __construct() {
        // Constructor can be extended to initialize database connection properties if needed
    }

    /**
     * Execute a given SQL query on the database.
     * 
     * @param string $query The SQL query to execute.
     * @return mixed $result The result of the query execution. Returns a result object on success or an empty string on failure.
     */
    public function executeQuery($query) {
        // Create a new MySQLi connection object using constants defined in the configuration file
        $mysqli = new mysqli(_DB_ADDRESS_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);

        // Check for a connection error and terminate if one occurs
        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        // Set the character set to UTF-8 and check for errors
        if (!$mysqli->set_charset("utf8")) {
            die("Error loading character set utf8: " . $mysqli->error);
        }

        // Check for any connection errors again and display an error message if one occurs
        if (mysqli_connect_errno()) {
            echo("Failed to connect to MySQL: " . mysqli_connect_error());
            return;
        }

        // Execute the SQL query and store the result
        if ($result = $mysqli->query($query)) {
            // SQL executed successfully
        } else {
            // SQL execution failed, set result to an empty string
            $result = "";
        }

        // Close the database connection
        $mysqli->close();

        // Return the result of the query
        return $result;
    }

    /**
     * Execute all SQL statements found in a specified file.
     * 
     * @param string $filename The path to the SQL file containing the queries to execute.
     */
    public function executeSQLFile($filename) {
        // Get the SQL queries from the file as a single string
        $tables = FileHandler::getStringFromFile($filename);

        // Tokenize the string, splitting on the semicolon to separate individual queries
        $tmp = strtok($tables, ";");

        // Create a new MySQLi connection object using constants defined in the configuration file
        $mysqli = new mysqli(_DB_ADDRESS_, _DB_USER_, _DB_PASSWD_, _DB_NAME_);

        // Check for a connection error and terminate if one occurs
        if ($mysqli->connect_errno > 0) {
            die('Unable to connect to database [' . $mysqli->connect_error . ']');
        }

        // Loop through each SQL statement in the file
        while ($tmp != false) {
            // Prepare the SQL statement for execution
            $stmt = $mysqli->prepare(utf8_decode($tmp));

            if (!$stmt) {
                // If the statement could not be prepared, print an error message
                if (!empty($tmp)) {
                    echo("Could not connect to Database<br>" . mysqli_error($mysqli) . "<br>host: " . _DB_ADDRESS_ . "<br>Query: <br>" . $tmp);
                }
            } else {
                // Execute the SQL statement
                $stmt->execute();
                // Close the statement
                $stmt->close();
            }
            // Move to the next SQL statement in the file
            $tmp = strtok(";");
        }

        // Close the database connection
        $mysqli->close();
    }

}
