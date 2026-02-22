<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

/**
 * Token-Based Authentication Guard
 * Validates API requests using Bearer token authentication
 */
class TokenGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $provider;
    protected $inputKey;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->inputKey = 'api_token';
    }

    /**
     * Get the currently authenticated user
     */
    public function user()
    {
        // If we already have a user, return it
        if (!is_null($this->user)) {
            return $this->user;
        }

        // Get the token from the request
        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            // Find user by token
            $this->user = $this->provider->retrieveByCredentials([
                $this->inputKey => $this->hashToken($token),
            ]);

            return $this->user;
        }

        return null;
    }

    /**
     * Validate a user's credentials
     */
    public function validate(array $credentials = [])
    {
        if (!isset($credentials[$this->inputKey])) {
            return false;
        }

        $credentials[$this->inputKey] = $this->hashToken($credentials[$this->inputKey]);

        return !is_null($this->provider->retrieveByCredentials($credentials));
    }

    /**
     * Get the token from the Authorization header or query string
     */
    protected function getTokenForRequest()
    {
        // First, try to get from Authorization header (Bearer token)
        $token = $this->request->bearerToken();

        if (!empty($token)) {
            return $token;
        }

        // Fall back to query string (for development/testing)
        return $this->request->query($this->inputKey);
    }

    /**
     * Hash the token for comparison
     */
    protected function hashToken($token)
    {
        return hash('sha256', $token);
    }
}
