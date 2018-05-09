<?php


/**
 *
 */
class User
{
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
	public function search():void
	{
		// TODO: implement here
	}

	/**
	 *
	 */
	public function login():void
	{
		// TODO: implement here
	}

	/**
	 *
	 */
	public function register():void
	{
		// TODO: implement here
	}

	/**
	 * admin -> { admin , client }
	 * client -> {admin}
	 */
	public function contact():void
	{
		// TODO: implement here
	}

	/**
	 *
	 */
	public function add_video():void
	{
		// TODO: implement here
	}
}
