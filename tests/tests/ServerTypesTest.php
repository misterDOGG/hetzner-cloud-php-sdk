<?php
/**
 * Created by PhpStorm.
 * User: lukaskammerling
 * Date: 11.07.18
 * Time: 18:31
 */

namespace Tests\tests;

use LKDev\HetznerCloud\Models\Datacenters\Datacenters;
use LKDev\HetznerCloud\Models\Locations\Locations;
use LKDev\HetznerCloud\Models\Servers\Types\ServerTypes;
use Tests\TestCase;

/**
 *
 */
class ServerTypesTest extends TestCase
{
    /**
     * @var  \LKDev\HetznerCloud\Models\Servers\Types\ServerTypes
     */
    protected $server_types;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->server_types = new ServerTypes();
    }

    /**
     *
     */
    public function testGet()
    {
        $server_type = $this->server_types->get(1);
        $this->assertEquals($server_type->id, 1);
        $this->assertEquals($server_type->name, 'cx11');
    }

    /**
     *
     */
    public function testAll()
    {
        $server_types = $this->server_types->all();

        $this->assertEquals(count($server_types), 1);
        $this->assertEquals($server_types[0]->id, 1);
        $this->assertEquals($server_types[0]->name, 'cx11');

    }
}