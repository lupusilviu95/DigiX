<?php
namespace App;
class GoogleLogin
{
  protected $client;

  public function __construct(\Google_Client $client)
  {
    
    $this->client = $client;
    $OAUTH2_CLIENT_ID = '775323648372-mt3jrs515l831bksj65jbdi5atftkjt6.apps.googleusercontent.com';
	$OAUTH2_CLIENT_SECRET = '4lmnufWoPUQXsoBDK_JA4rz9';
    $this->client->setClientId($OAUTH2_CLIENT_ID);
    $this->client->setClientSecret($OAUTH2_CLIENT_SECRET);
    $this->client->setRedirectUri("http://localhost:8000/loginCallback");
    $this->client->setScopes('https://www.googleapis.com/auth/youtube');
    
  }

  public function isLoggedIn()
  {
    if (\Session::has('token')) {
      $this->client->setAccessToken(\Session::get('token'));
    }

    if ($this->client->isAccessTokenExpired()) {
      \Session::set('token', $this->client->getRefreshToken());
    }

    return !$this->client->isAccessTokenExpired();
  }

  public function login($code)
  {
    $this->client->authenticate($code);
    $token = $this->client->getAccessToken();
    \Session::put('token', $token);

    return $token;
  }
  
  public function getLoginUrl()
  {
    $authUrl = $this->client->createAuthUrl();

    return $authUrl;
  }
}