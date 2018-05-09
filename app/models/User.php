<?php

namespace MoviesBox;

/**
 *
 */
class User
{

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    private $first_name;

    /**
     * @var string
     */
    private $last_name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     *
     */
    public function __construct(string $username, string $first_name, string $last_name, string $email, string $password)
    {
        $this->username = $username;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     *
     */
    public function search(): void
    {
        // TODO: implement here
    }

    /**
     * Perform database check for login process.
     * @param string $username
     * @param string $password
     * @return mixed false or User instance
     * @throws DBCException can't establish connection
     */
    public static function login(string $username, string $password)
    {
        $db = new Oracle();
        $db->connect(USERNAME, PASSWORD);

        $query = 'SELECT password
                    FROM user_
                  WHERE username = :username';
        $result = $db->qin($query, ["username" => $username]);
        if (($result = self::fetch($result)) == false) {
            return false;
        }

        if (password_verify($password, $result[0]["PASSWORD"])) {
            $query = 'SELECT NAME, LAST_NAME, EMAIL FROM "user" WHERE USERNAME = :username';
            $result = $db->qin($query, ["username" => $username]);
            if (($result = self::fetch($result)) == false) {
                return false;
            }
            // no sense ?
            $first_name = $result[0]["NAME"];
            $last_name = $result[0]["LAST_NAME"];
            $email = $result[0]["EMAIL"];

            return new User($username, $first_name, $last_name, $email, "");
        } else {
            return false;
        }
    }

    /**
     * @param bool $asAdmin by default user is added as client unless $asAdmin = true
     */
    public function register(bool $asAdmin = false): bool
    {
        $db = new Oracle();
        $db->connect(USERNAME, PASSWORD);

        /****************** Username existance ***********************/
        $query = 'SELECT Count(username) EXIST
					FROM user_
				WHERE username = :username';
        $result = $db->qin($query, [":username" => $this->username]);
        if (($result = self::fetch($result)) == false) {
            return false;
        } else if ($result[0]["EXIST"] == 1) {
            throw new DBCException("username already exists");
        }
        /************************************************************/

        /***************** User register  ***************************/
        $query = "INSERT INTO user_ VALUES( :username, :pwd, :first_name, :last_name, :email)";
        $data = [
            ":username" => $this->username,
            ":pwd" => password_hash($this->password, PASSWORD_BCRYPT),
            ":first_name" => $this->first_name,
            ":last_name" => $this->last_name,
            ":email" => $this->email,
        ];
        $result = $db->qout($query, $data);
        if ($result["success"] == false) {
            throw new DBCException($result["data"]);
        }

        /*************************************************************/

        if ($asAdmin == false) {
            return $this->registerByRole($db, 'client');
        } else {
            return $this->registerByRole($db, 'admin');
        }

    }

    /**
     * Used to register Client/Admin
     * @param Oracle $db oracleDB instance
     * @param string $role is actually the tableName client/admin
     * @throws DBCException
     */
    private function registerByRole(Oracle $db, String $role)
    {
        $query = "INSERT into $role(username) VALUES(
			:username
		  )";

        $result = $db->qout($query, ["username" => $this->username]);
        if ($result["success"] == false) {
            throw new DBCException($result["data"]);
        }
        $this->password=""; //TODO: in case of object serialisation.
        return true;
    }

    /**
     * admin -> { admin , client }
     * client -> {admin}
     */
    public function contact(): void
    {
        // TODO: implement here
    }

    /**
     *
     */
    public function add_video(): void
    {
        // TODO: implement here
    }

    /**
     *
     * @throws DBCException
     */
    public static function fetch(array $result)
    {
        if (empty($result["success"])) {
            throw new DBCException("no result found");
        } else if (empty($result["data"])) {
            return false;
        } else {
            return $result["data"];
        }
    }
}