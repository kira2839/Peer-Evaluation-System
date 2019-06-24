<?php

include_once("website_page_handle.php");
//Use the static method getInstance to get the object

/**
 * @property mixed created_time
 * @property mixed agent
 */
class Session
{
    const SESSION_STARTED = TRUE;
    const SESSION_NOT_STARTED = FALSE;
    const COOKIE_NAME = "EvaluationSystem";
    const SESSION_EXPIRE_TIME_IN_SEC = 900;

    // The state of the session
    private $sessionState = self::SESSION_NOT_STARTED;

    // The only instance of the class
    private static $instance;

    private function __construct()
    {
    }

    /**
     *    Returns The instance of 'Session'.
     *    The session is automatically initialized if it wasn't.
     * @return    object
     **/
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        self::$instance->startSession();
        return self::$instance;
    }

    /**
     *    (Re)starts the session.
     *
     * @return    bool    TRUE if the session has been initialized, else FALSE.
     **/
    public function startSession()
    {
        if ($this->sessionState == self::SESSION_NOT_STARTED) {
            //require the use of a session cookie (as sessions
            //can work without cookies but it’s less secure)
            ini_set('session.use_only_cookies', 1);

            session_name(self::COOKIE_NAME);
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }

    public function isSessionValid()
    {
        if (!isset($this->email_id) OR
            ($this->agent != sha1($_SERVER['HTTP_USER_AGENT'])) OR
            !isset($this->created_time) OR
            (time() - $this->created_time > self::SESSION_EXPIRE_TIME_IN_SEC)) {
            return false;
        } else {
            return true;
        }
    }

    public function displaySessionExpiredMessage()
    {
        echo <<<EOC
    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; display: inline-block;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		Session expired. Please login again using get code</p>
	</div>
EOC;
    }

    /**
     *    Stores datas in the session.
     *    Example: $instance->foo = 'bar';
     *
     * @param name    Name of the datas.
     * @param value    Your datas.
     * @return    void
     **/
    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     *    Gets datas from the session.
     *    Example: echo $instance->foo;
     *
     * @param name    Name of the datas to get.
     * @return    mixed    Datas stored in session.
     **/
    public function __get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }

    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     *    Destroys the current session.
     *
     * @return    bool    TRUE is session has been deleted, else FALSE.
     **/
    public function destroy()
    {
        if ($this->sessionState == self::SESSION_STARTED) {
            $this->sessionState = !session_destroy();
            unset($_SESSION);

            // Destroy the cookie
            setcookie(self::COOKIE_NAME, '', time() - 3600, '/', '', 0, 0);
            return !$this->sessionState;
        }

        return FALSE;
    }
}