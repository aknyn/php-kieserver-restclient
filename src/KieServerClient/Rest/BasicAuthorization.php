<?php
namespace KieServerClient\Rest;

/**
 * Class BasicAuthorization
 *
 * @author Alexander Knyn <ich@alexander-knyn.de>
 * @package KieServerClient\Rest
 */
class BasicAuthorization
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * BasicAuthorization constructor.
     *
     * @param string $user
     * @param string $password
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * get token for basic auth
     *
     * @return string
     */
    public function getAuthToken()
    {
        $token = base64_encode("{$this->getUser()}:{$this->getPassword()}");
        return "Basic {$token}";
    }

}
