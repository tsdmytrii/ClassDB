<html>
    <body>

        <?php
        // Connection information
        defined('DB_HOST') ? NULL : define('DB_HOST', 'localhost');
        defined('DB_USER') ? NULL : define('DB_USER', 'root');
        defined('DB_PASS') ? NULL : define('DB_PASS', 'root');
        defined('DB_NAME') ? NULL : define('DB_NAME', 'CLassDB');

        class DB {

            private $_connection;
            public $result;
            private static $instance = null;

            // Start function
            private function __construct() {
                $this->connect();
            }

            //Opening the connection only when first time function  is called
            public static function getInstance() {
                if (self::$instance == null) {
                    self::$instance = new DB();
                }
                return self::$instance;
            }
                
            //Connection to database
            private function connect() {
                $this->_connection = mysql_connect(DB_HOST, DB_USER, DB_PASS);
                if (!$this->_connection)
                    die('Database connection failed: ' . mysql_error());
                else {
                    $db_select = mysql_select_db(DB_NAME, $this->_connection);
                    echo "connected<br>";
                    if (!$db_select)
                        die('Database selection failed: ' . mysql_error());
                }
            }

            //Query to databse by using $sql variable 
            public function select($sql) {
                $result = mysql_query($sql, $this->_connection);
                $this->_check($result);
                while ($array = mysql_fetch_assoc($result)) {
                    print_r($array);
                    echo "<br>";
                }
                echo "<br>";
                return $result;
            }

            //Cheking if query result is not empty
            private function _check($result) {
                if (mysql_num_rows($result) < 1) {
                    $output = "Database query failed: " . mysql_error();
                    die($output);
                }
            }

        }

        //Getting data for query 
        $fields = ($_GET['fields']);
        $table = ($_GET['table']);
        $where = ($_GET['where']);
        $limit = ($_GET['limit']);
        $offset = ($_GET['offset']);

        //Validation
        //for fields
        if (!preg_match("/^[a-zA-Z0-9_*,]+$/", $fields)) {
            exit("Wrong fields part");
        }
        //for table
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $table)) {
            exit("Wrong table");
        }
        $sql = "";

        //if fields and table are selected
        if (!empty($fields) && !empty($table)) {
            $sql = "SELECT " . "$fields " . "FROM " . "$table ";

            //for where part
            if (!empty($where)) {
                if (!preg_match("/^[a-zA-Z0-9<>=&#]+$/", $where)) {
                    exit("Wrong where part");
                }
                $sql .= "WHERE $where ";
            }
            //for limit
            if (!empty($limit)) {
                //for offset 
                if (!empty($offset)) {
                    if (!preg_match("/^[0-9]+$/", $offset)) {
                        exit("Wrong offset part");
                    }
                    $sql .= "LIMIT $offset";
                } else {
                    $sql .= "LIMIT 0";
                }

                if (!preg_match("/^[0-9]+$/", $limit)) {
                    exit("Wrong limit part");
                }
                $sql .= ", $limit";
            } else {
                if (!empty($offset)) {
                    if (!preg_match("/^[0-9]+$/", $offset)) {
                        exit("Wrong offset part");
                    }
                    $sql .= "LIMIT $offset, 30";
                }
            }
            echo "Your query -  $sql<br>";
        } else {

            echo "SELECT fields and table, please<br>";
        }

        $db1 = DB::getInstance()->select($sql);
        $db1 = DB::getInstance()->select($sql);
        $db1 = DB::getInstance()->select($sql);

        //$db2 = DB::getInstance()->select($sql);
        ?>

    </body>
</html>