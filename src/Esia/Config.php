<?php

namespace Esia;

use Esia\Exceptions\InvalidConfigurationException;

class Config
{
    private $clientId;
    private $redirectUrl;
    private $privateKeyPath;
    private $certPath;

    private $useCli;
    private $useGost;

    private $portalUrl = 'http://esia-portal1.test.gosuslugi.ru/';
    private $tokenUrlPath = 'aas/oauth2/te';
    private $codeUrlPath = 'aas/oauth2/ac';
    private $personUrlPath = 'rs/prns';
    private $logoutUrlPath = 'idp/ext/Logout';
    private $privateKeyPassword = '';

    /**
     * @var string[]
     */
    private $scope = [
        'fullname',
        'birthdate',
        'gender',
        'email',
        'mobile',
        'id_doc',
        'snils',
        'inn',
    ];

    private $tmpPath = '/var/tmp';

    private $responseType = 'code';
    private $accessType = 'offline';

    private $token = '';
    private $tokenExpiresIn = '';
    private $oid = '';
    private $refreshToken = '';

    /**
     * Config constructor.
     *
     * @param array $config
     * @throws InvalidConfigurationException
     */
    public function __construct(array $config = [])
    {
        // Required params
        $this->clientId = $config['clientId'] ?? $this->clientId;
        if (!$this->clientId) {
            throw new InvalidConfigurationException('Please provide clientId');
        }

        $this->redirectUrl = $config['redirectUrl'] ?? $this->redirectUrl;
        if (!$this->redirectUrl) {
            throw new InvalidConfigurationException('Please provide redirectUrl');
        }

        $this->privateKeyPath = $config['privateKeyPath'] ?? $this->privateKeyPath;
        if (!$this->privateKeyPath) {
            throw new InvalidConfigurationException('Please provide privateKeyPath');
        }
        $this->certPath = $config['certPath'] ?? $this->certPath;
        if (!$this->certPath) {
            throw new InvalidConfigurationException('Please provide certPath');
        }

        $this->useCli = $config['useCli'] ?? false;
        $this->useGost = $config['useGost'] ?? false;

        $this->portalUrl = $config['portalUrl'] ?? $this->portalUrl;
        $this->tokenUrlPath = $config['tokenUrlPath'] ?? $this->tokenUrlPath;
        $this->codeUrlPath = $config['codeUrlPath'] ?? $this->codeUrlPath;
        $this->personUrlPath = $config['personUrlPath'] ?? $this->personUrlPath;
        $this->logoutUrlPath = $config['logoutUrlPath'] ?? $this->logoutUrlPath;
        $this->privateKeyPassword = $config['privateKeyPassword'] ?? $this->privateKeyPassword;
        $this->oid = $config['oid'] ?? $this->oid;
        $this->scope = $config['scope'] ?? $this->scope;
        if (!is_array($this->scope)) {
            throw new InvalidConfigurationException('scope must be array of strings');
        }

        $this->responseType = $config['responseType'] ?? $this->responseType;
        $this->accessType = $config['accessType'] ?? $this->accessType;
        $this->tmpPath = $config['tmpPath'] ?? $this->tmpPath;
        $this->token = $config['token'] ?? $this->token;
        $this->refreshToken = $config['refreshToken'] ?? $this->refreshToken;
        $this->tokenExpiresIn = $config['tokenExpiresIn'] ?? $this->tokenExpiresIn;
    }

    public function getPortalUrl(): string
    {
        return $this->portalUrl;
    }

    public function getPrivateKeyPath(): string
    {
        return $this->privateKeyPath;
    }

    public function getPrivateKeyPassword(): string
    {
        return $this->privateKeyPassword;
    }

    public function getCertPath(): string
    {
        return $this->certPath;
    }
    
    public function getOid(): string
    {
        return $this->oid;
    }

    public function setOid(string $oid): void
    {
        $this->oid = $oid;
    }

    public function getScope(): array
    {
        return $this->scope;
    }

    public function getScopeString(): string
    {
        return implode(' ', $this->scope);
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }

    public function getAccessType(): string
    {
        return $this->accessType;
    }

    public function getTmpPath(): string
    {
        return $this->tmpPath;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /*
     * Returns expiration time in seconds
     */
    public function getTokenExpiresIn(): ?string {
        return $this->tokenExpiresIn;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setTokenExpiresIn(string $seconds): void {
        $this->tokenExpiresIn = $seconds;
    }


    public function getRefreshToken(): ?string {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void {
        $this->refreshToken = $refreshToken;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    /**
     * Return an url for request to get an access token
     */
    public function getTokenUrl(): string
    {
        return $this->portalUrl . $this->tokenUrlPath;
    }

    /**
     * Return an url for request to get an authorization code
     */
    public function getCodeUrl(): string
    {
        return $this->portalUrl . $this->codeUrlPath;
    }

    /**
     * @return string
     * @throws InvalidConfigurationException
     */
    public function getPersonUrl(): string
    {
        if (!$this->oid) {
            throw new InvalidConfigurationException('Please provide oid');
        }
        return $this->portalUrl . $this->personUrlPath . '/' . $this->oid;
    }

    /**
     * Return an url for logout
     */    
    public function getLogoutUrl(): string 
    {
        return $this->portalUrl . $this->logoutUrlPath;
    }

    /**
     * Return a param telling us whether we should use CliSignerPKCS7 for signing requests.
     * @return bool
     */
    public function getUseCli(): bool {
        return $this->useCli;
    }

    /**
     * Return a param telling us whether we should use gost engine for OpenSSL. Requires OpenSSL to be configured with
     * gost algorithms enabled.
     * @return bool
     */
    public function getUseGost(): bool {
        return $this->useGost;
    }
}
