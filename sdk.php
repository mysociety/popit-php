<?php

if (!function_exists('curl_init')) {
  throw new Exception('CURL PHP extension is required.');
}
if (!function_exists('json_decode')) {
  throw new Exception('JSON PHP extension is required.');
}

/**
 * @author Chetan Bansal <chetan1@gmail.com>
 */
class PopIt
{
  /**
   * Version.
   */
  const VERSION = 'v1';

  /**
   * Hostname of the host server
   */
  const HOSTNAME = "popit.mysociety.org";


  /**
   * Default options for curl.
   */
  public static $CURL_OPTS = array(
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_USERAGENT      => 'popit-php-1.0',
  );

  /**
   * The Instance Name
   */
  protected $instanceName;

  /**
   * The user name provided.
   */
  protected $user;

  /**
   * The password provided
   */
  protected $password;

  /**
   * The URL for the API endpoint
   */
  protected $baseURL;


  /**
   * Initialize a PopIt Application.
   *
   * The configuration:
   * - instanceName: name of the instance
   * - user: (optional) username for authentication
   * - password: (optional) password for authentication
   *
   * @param Array $config the application configuration
   */
  public function __construct($config) {

        if (!isset($config['instanceName'])) {
            throw new Exception('Error: NULL Instance Name.');
        }
        else
        {
            $this->instanceName = $config['instanceName'];
            $this->setBaseUrl($config['instanceName']);
        }

        if (isset($config['user']))
            $this->user = $config['user'];

        if (isset($config['password']))
            $this->password = $config['password'];
  }

  public function getInstanceName() {
    return $this->instanceName;
  }

  /**
   * Set the API endpoint URL.
   */
  protected function setBaseUrl($instanceName) {
    $this->baseURL = "http://$instanceName." . self::HOSTNAME . "/api/" . self::VERSION . "/";
  }

  /**
   * Get the API endpoint URL.
   */
  public function getBaseUrl() {
    return $this->baseURL;
  }

  /**
   * Make the API Call
   *
   * @param String $path the path (required)
   * @param String $method the http method (default 'GET')
   * @param Array $params the query data
   * @return the decoded response object
   */
  public function call($path, $method='GET', $params=array()) {
    
    if (!isset($path)) {
        throw new Exception('Error: NULL API Path.');
    }
    else
        $url = $this->getBaseUrl() . $path;

    $result = json_decode($this->makeRequest(
      $url,
      strtoupper($method),
      $params
    ), true);

    // check for errors
    if (is_array($result) && (isset($result['error']) || isset($result['errors']))) {
        if(isset($result['error']))
            throw new Exception('Error: ' . $result['error']);
        else
            throw new Exception(json_encode($result));
    }

    return $result;
  }

  /**
   * Makes an HTTP request. This method can be overriden by subclasses if
   * developers want to do fancier things or use something other than curl to
   * make the request.
   *
   * @param String $url the URL to make the request to
   * @param String $method the type of request
   * @param Array $params the parameters to use for the POST body
   * @return String the response text
   */
  protected function makeRequest($url, $method, $params) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $opts = self::$CURL_OPTS;
    
    switch ($method) {
      case 'GET':
        break;
      case 'POST':
        $opts[CURLOPT_POST] = true;
        break;
      default:
        $opts[CURLOPT_CUSTOMREQUEST] = $method;
    }

    if($method != "GET" && (!$this->user || !$this->password))
        throw new Exception('Error: Authentication Required, Username and/or password not set.');
    else
    {
        $opts[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
        $opts[CURLOPT_USERPWD] = "{$this->user}:{$this->password}";
    }

    if($method == "PUT" || $method == "POST")
        $opts[CURLOPT_POSTFIELDS] = http_build_query($params);

    $opts[CURLOPT_URL] = $url;
    curl_setopt_array($ch, $opts);

    $result = curl_exec($ch);
    
//    $response = curl_getinfo($ch);
//    print_r($response);

    if ($result === false) {
      throw new Exception('Error: CURL Exception.');
      curl_close($ch);
    }

    curl_close($ch);
    return $result;
  }
}
