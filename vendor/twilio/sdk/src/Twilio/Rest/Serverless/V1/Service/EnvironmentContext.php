<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Serverless
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Serverless\V1\Service;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Version;
use Twilio\InstanceContext;
use Twilio\Rest\Serverless\V1\Service\Environment\LogList;
use Twilio\Rest\Serverless\V1\Service\Environment\DeploymentList;
use Twilio\Rest\Serverless\V1\Service\Environment\VariableList;


/**
 * @property LogList $logs
 * @property DeploymentList $deployments
 * @property VariableList $variables
 * @method \Twilio\Rest\Serverless\V1\Service\Environment\LogContext logs(string $sid)
 * @method \Twilio\Rest\Serverless\V1\Service\Environment\VariableContext variables(string $sid)
 * @method \Twilio\Rest\Serverless\V1\Service\Environment\DeploymentContext deployments(string $sid)
 */
class EnvironmentContext extends InstanceContext
    {
    protected $_logs;
    protected $_deployments;
    protected $_variables;

    /**
     * Initialize the EnvironmentContext
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid The SID of the Service to create the Environment resource under.
     * @param string $sid The SID of the Environment resource to delete.
     */
    public function __construct(
        Version $version,
        $serviceSid,
        $sid
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'serviceSid' =>
            $serviceSid,
        'sid' =>
            $sid,
        ];

        $this->uri = '/Services/' . \rawurlencode($serviceSid)
        .'/Environments/' . \rawurlencode($sid)
        .'';
    }

    /**
     * Delete the EnvironmentInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        return $this->version->delete('DELETE', $this->uri);
    }


    /**
     * Fetch the EnvironmentInstance
     *
     * @return EnvironmentInstance Fetched EnvironmentInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): EnvironmentInstance
    {

        $payload = $this->version->fetch('GET', $this->uri, [], []);

        return new EnvironmentInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['sid']
        );
    }


    /**
     * Access the logs
     */
    protected function getLogs(): LogList
    {
        if (!$this->_logs) {
            $this->_logs = new LogList(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }

        return $this->_logs;
    }

    /**
     * Access the deployments
     */
    protected function getDeployments(): DeploymentList
    {
        if (!$this->_deployments) {
            $this->_deployments = new DeploymentList(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }

        return $this->_deployments;
    }

    /**
     * Access the variables
     */
    protected function getVariables(): VariableList
    {
        if (!$this->_variables) {
            $this->_variables = new VariableList(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }

        return $this->_variables;
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get(string $name): ListResource
    {
        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call(string $name, array $arguments): InstanceContext
    {
        $property = $this->$name;
        if (\method_exists($property, 'getContext')) {
            return \call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Serverless.V1.EnvironmentContext ' . \implode(' ', $context) . ']';
    }
}
