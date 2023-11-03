<?php
namespace Kosma\Nodes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NodeConnection
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function checkStatus($host, $token)
    {
        try {
            $headers = [
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ];
            $options = [
                'headers' => $headers,
            ];
            $response = $this->client->get($host . '/system/info', $options);

            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                return 'online';
            } else if ($statusCode == 503) {
                return 'offline';
            } else if ($statusCode == 401) {
                return 'unauthorized';
            } else {
                return $statusCode;
            }
        } catch (RequestException $e) {
            return 'An error occurred while checking ' . $host . '';
        }
    }
    public function getNodeInfo($host, $token)
    {
        try {
            $headers = [
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ];
            $options = [
                'headers' => $headers,
            ];
            $response = $this->client->get($host . '/system/info', $options);

            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $json_data = $response->getBody()->getContents();
                return json_decode($json_data, true);
            } else {
                return null;
            }
        } catch (RequestException $e) {
            return null;
        }
    }

    public function AdvancedCheckStatus($host, $token)
    {
        $headers = [
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ];
        $options = [
            'headers' => $headers,
        ];
        $response = $this->client->get($host . '/system/info', $options);


        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            return 'online';
        } else if ($statusCode == 503) {
            return 'The node is offline';
        } else if ($statusCode == 401) {
            return 'Server token mismatch: please check if this is the right server token to authenticate with the node and panel';
        } else {
            return $statusCode;
        }

    }
    public function ShutDownNode($host, $token)
    {
        $headers = [
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ];
        $options = [
            'headers' => $headers,
        ];
        $response = $this->client->post($host . '/system/shutdown', $options);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            return 'online';
        } else if ($statusCode == 503) {
            return 'The node is offline';
        } else if ($statusCode == 401) {
            return 'Server token mismatch: please check if this is the right server token to authenticate with the node and panel';
        } else {
            return $statusCode;
        }

    }
    public function RebootNode($host, $token)
    {
        $headers = [
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ];
        $options = [
            'headers' => $headers,
        ];
        $response = $this->client->post($host . '/system/reboot', $options);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 200) {
            return 'online';
        } else if ($statusCode == 503) {
            return 'The node is offline';
        } else if ($statusCode == 401) {
            return 'Server token mismatch: please check if this is the right server token to authenticate with the node and panel';
        } else {
            return $statusCode;
        }

    }
}
?>