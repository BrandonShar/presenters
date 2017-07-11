<?php

namespace Examples;

use DateTime;
use brandonshar\Presenter;

require_once 'vendor/autoload.php';

class Vehicle
{
    public $title = '2014 Fiat 500L';
    public $condition = 'Used';
}

class Dealership
{
    public $name = 'Value Cars';
    public $city = 'Boston';
    public $state = 'MA';
}

class VehiclePresenter extends Presenter
{
    protected $vehicle;
    protected $dealership;

    protected $delegatesTo = [
        'vehicle'       => ['title', 'condition'],
        'dealership'    => ['name'],
    ];

    public function __construct(Vehicle $vehicle, Dealership $dealership)
    {
        $this->vehicle = $vehicle;
        $this->dealership = $dealership;
    }

    public function getAddressAttribute()
    {
        return "{$this->dealership->city}, {$this->dealership->state}";
    }

    public function getDataPulledOnAttribute($passedInDate)
    {
        return DateTime::createFromFormat('Y-m-d', $passedInDate)->format('l, M jS');
    }
}

$vehicle = new Vehicle;
$dealership = new Dealership;
$vehiclePresenter = VehiclePresenter::present($vehicle, $dealership)->tap(function ($p) {
    $p->dataPulledOn = date('Y-m-d');
});

echo json_encode($vehiclePresenter) . PHP_EOL;

//{"title":"2014 Fiat 500L","condition":"Used","name":"Value Cars","data_pulled_on":"Tuesday, Jul 11th","address":"Boston, MA"}




