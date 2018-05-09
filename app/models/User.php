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
                    FROM "user"
                  WHERE username = :username';
		$result = $db->qin($query, ["username" => $username]);
		if(($result = self::fetch($result)) == false)
			return false;
		
		if (password_verify($password, $result[0]["PASSWORD"]))
		{
			$query = 'SELECT NAME, LAST_NAME, EMAIL FROM "user" WHERE USERNAME = :username';
			$result = $db->qin($query, ["username"=>$username]);
			if(($result = self::fetch($result)) == false)
				return false; // no sense ? 
			$first_name = $result[0]["NAME"];
			$last_name = $result[0]["LAST_NAME"];
			$email = $result[0]["EMAIL"];

			return new User($username, $first_name, $last_name, $email, "");
		}else{
			return false;
		}
    }

    /**
     *
     */
    public function register(): bool
    {
        $db = new Oracle();
		$db->connect(USERNAME, PASSWORD);
		$query = 'INSERT into "user" VALUES (
			:username,
			:password,
			:first_name,
			:last_name,
			:email
		  )';
		$data = [
			"username" => $this->username,
			"password" => $this->password,
			"first_name"=> $this->first_name,
			"last_name" => $this->last_name,
			"email" => $this->email
		];
		$result = $db->qout($query, $data);
		if($result["success"] == false){
			throw new DBCException($result["data"], 1);
		}
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
	public static function fetch(array $result){
		if(empty($result["success"])){
			throw new DBCException("no result found");
		}
		else if(empty($result["data"])){
			return false;
		}
		else{
			return $result["data"];
		}
	}
}
