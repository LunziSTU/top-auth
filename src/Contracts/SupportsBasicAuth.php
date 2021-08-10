<?php

namespace Lunzi\TopAuth\Contracts;

interface SupportsBasicAuth
{
    /**
     * Attempt to authenticate using HTTP Basic Auth.
     * @param string $field
     * @param array $extraConditions
     * @return mixed
     */
    public function basic($field = 'email', $extraConditions = []);

    /**
     * Perform a stateless HTTP Basic login attempt.
     * @param string $field
     * @param array $extraConditions
     * @return mixed
     */
    public function onceBasic($field = 'email', $extraConditions = []);
}