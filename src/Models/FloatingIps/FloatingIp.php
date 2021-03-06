<?php
/**
 * Created by PhpStorm.
 * User: lukaskammerling
 * Date: 28.01.18
 * Time: 20:59
 */

namespace LKDev\HetznerCloud\Models\FloatingIps;

use LKDev\HetznerCloud\ApiResponse;
use LKDev\HetznerCloud\HetznerAPIClient;
use LKDev\HetznerCloud\Models\Actions\Action;
use LKDev\HetznerCloud\Models\Locations\Location;
use LKDev\HetznerCloud\Models\Model;
use LKDev\HetznerCloud\Models\Protection;
use LKDev\HetznerCloud\Models\Servers\Server;

/**
 *
 */
class FloatingIp extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $ip;
    /**
     * @var string
     */
    public $type;
    /**
     * @var int
     */
    public $server;

    /**
     * @var array
     */
    public $dnsPtr;

    /**
     * @var \LKDev\HetznerCloud\Models\Locations\Location
     */
    public $homeLocation;

    /**
     * @var bool
     */
    public $blocked;

    /**
     * @var array|\LKDev\HetznerCloud\Models\Protection
     */
    public $protection;

    /**
     * FloatingIp constructor.
     *
     * @param int $id
     * @param string $description
     * @param string $ip
     * @param string $type
     * @param int $server
     * @param array $dnsPtr
     * @param \LKDev\HetznerCloud\Models\Locations\Location $homeLocation
     * @param bool $blocked
     * @param Protection $protection
     */
    public function __construct(
        int $id,
        string $description,
        string $ip,
        string $type,
        int $server,
        array $dnsPtr,
        Location $homeLocation,
        bool $blocked,
        Protection $protection
    )
    {
        $this->id = $id;
        $this->description = $description;
        $this->ip = $ip;
        $this->type = $type;
        $this->server = $server;
        $this->dnsPtr = $dnsPtr;
        $this->homeLocation = $homeLocation;
        $this->blocked = $blocked;
        $this->protection = $protection;
        parent::__construct();
    }

    /**
     * Changes the description of a Floating IP.
     *
     * @see https://docs.hetzner.cloud/#resources-floating-ips-put
     * @param string $description
     * @return static
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function changeDescription(string $description): FloatingIp
    {
        $response = $this->httpClient->put('floating_ips/' . $this->id, [
            'json' => [
                'description' => $description,
            ],
        ]);
        if (!HetznerAPIClient::hasError($response)) {
            $this->description = $description;
            return self::parse(json_decode((string)$response->getBody())->floating_ip);
        }
    }

    /**
     * Deletes a Floating IP. If it is currently assigned to a server it will automatically get unassigned.
     *
     * @see https://docs.hetzner.cloud/#resources-floating-ips-delete
     * @return bool
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function delete(): bool
    {
        $response = $this->httpClient->delete('floating_ips/' . $this->id);
        if (!HetznerAPIClient::hasError($response)) {
            return true;
        }
    }

    /**
     * Changes the protection configuration of the Floating IP.
     *
     * @see https://docs.hetzner.cloud/#resources-floating-ip-actions-post-3
     * @param bool $delete
     * @return ApiResponse
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function changeProtection(bool $delete = true): ApiResponse
    {
        $response = $this->httpClient->post('floating_ips/' . $this->id . '/actions/change_protection', [
            'json' => [
                'delete' => $delete,
            ],
        ]);
        if (!HetznerAPIClient::hasError($response)) {
            return ApiResponse::create([
                'action' => Action::parse(json_decode((string)$response->getBody())->action)
            ]);
        }
    }

    /**
     * Assigns a Floating IP to a server
     *
     * @see https://docs.hetzner.cloud/#floating-ip-actions-assign-a-floating-ip-to-a-server
     * @param Server $server
     * @return ApiResponse
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function assignTo(Server $server): ApiResponse
    {
        $response = $this->httpClient->post('floating_ips/' . $this->id . '/actions/assign', [
            'json' => [
                'server' => $server->id,
            ],
        ]);
        if (!HetznerAPIClient::hasError($response)) {
            return ApiResponse::create([
                'action' => Action::parse(json_decode((string)$response->getBody())->action)
            ]);
        }
    }

    /**
     * Unassigns a Floating IP, resulting in it being unreachable. You may assign it to a server again at a later time.
     *
     * @see https://docs.hetzner.cloud/#floating-ip-actions-unassign-a-floating-ip
     * @return ApiResponse
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function unassign(): ApiResponse
    {
        $response = $this->httpClient->post('floating_ips/' . $this->id . '/actions/unassign');
        if (!HetznerAPIClient::hasError($response)) {
            return ApiResponse::create([
                'action' => Action::parse(json_decode((string)$response->getBody())->action)
            ]);
        }
    }

    /**
     * Changes the hostname that will appear when getting the hostname belonging to this Floating IP.
     *
     * @see https://docs.hetzner.cloud/#floating-ip-actions-change-reverse-dns-entry-for-a-floating-ip
     * @param string $ip
     * @param string $dnsPtr
     * @return ApiResponse
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function changeReverseDNS(string $ip, string $dnsPtr): ApiResponse
    {
        $response = $this->httpClient->post('floating_ips/' . $this->id . '/actions/change_dns_ptr', [
            'json' => [
                'ip' => $ip,
                'dns_ptr' => $dnsPtr,
            ],
        ]);
        if (!HetznerAPIClient::hasError($response)) {
            return ApiResponse::create([
                'action' => Action::parse(json_decode((string)$response->getBody())->action)
            ]);
        }
    }

    /**
     * @param  $input
     * @return \LKDev\HetznerCloud\Models\FloatingIps\FloatingIp|static
     */
    public static function parse($input): FloatingIp
    {
        if ($input == null) {
            return null;
        }

        return new self($input->id, $input->description, $input->ip, $input->type, $input->server, $input->dns_ptr, Location::parse($input->home_location), $input->blocked, Protection::parse($input->protection));
    }
}