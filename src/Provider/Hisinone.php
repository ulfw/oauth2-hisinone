<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\Exception\HisinoneIdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Hisinone extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public $domain = '';

    /**
     * Client-ID
     *
     * @var string
     */
    protected $clientId = '';

    /**
     * Response Type
     *
     * @var string
     */
    protected $responseType = 'code';

    /**
     * State
     *
     * @var string
     */
    protected $state = '';
    
    /**
     * Scope
     *
     * @var string
     */
    protected $scope = 'code';

    /**
     * set domain
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * set Client-ID
     */
    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/hisinone/pages/oauth.faces';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . '/hisinone/api/v1/cs/psv/oauth/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->domain . '/hisinone/api/v1/cs/psv/oauth/userinfo';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     *
     * @link   https://developer.github.com/v3/#client-errors
     * @link   https://developer.github.com/v3/oauth/#common-errors-for-the-access-token-request
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array             $data     Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw HisinoneIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw HisinoneIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param  array       $response
     * @param  AccessToken $token
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new HisinoneResourceOwner($response);

        return $user->setDomain($this->domain);
    }
}
