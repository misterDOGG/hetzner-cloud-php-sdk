<?php
/**
 * Created by PhpStorm.
 * User: lukaskammerling
 * Date: 28.01.18
 * Time: 21:00
 */

namespace LKDev\HetznerCloud\Models\Locations;

use LKDev\HetznerCloud\Models\Model;

/**
 *
 */
class Location extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $city;

    /**
     * @var float
     */
    public $latitude;

    /**
     * @var float
     */
    public $longitude;

    /**
     * Location constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $country
     * @param string $city
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(
        int $id,
        string $name,
        string $description,
        string $country,
        string $city,
        float $latitude,
        float $longitude
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->country = $country;
        $this->city = $city;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        parent::__construct();
    }

    /**
     * @param $input
     * @return \LKDev\HetznerCloud\Models\Locations\Location|static
     */
    public static function parse($input)
    {
        if ($input == null) {
            return null;
        }
        return new self($input->id, $input->name, $input->description, $input->country, $input->city, $input->latitude, $input->longitude);
    }
}