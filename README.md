# Presenters

**WARNING** While this is tested, I have not used it in a production or extensively in a development environment yet. I'm pretty sure I'm going to love using it, but until I do that I feel obligated to offer a warning about its usefulness. 

### So what is this?
Have you ever had a model, or combination of models, that you wanted to return with different data depending on the endpoint? Maybe you have a resource controller called `VehicleOnCraigslistController` and when someone hits your `VehicleOnCraigslistController@show` method, you want to return a set of data that represents a vehicle listed on Craigslist. This set of data is likely comprised of your `Vehicle` model and your `CraigslistAd` model, but different than what you return from your `VehicleController@show` method, your `CraigslistAdController@show`, or just a combination of the two. This is where **Presenters** come in to save the day!

### How do I do those great sounding things?
Presenters have a number of great features. Let's see them all in action!

#### Basic Presenter Example
```php
class VehicleOnCraigslistPresenter extends Presenter 
{
  protected $vehicle;
  protected $craigslistAd;
  
  public function __construct(Vehicle $vehicle, CraigslistAd $craigslistAd)
  {
    $this->vehicle = $vehicle;
    $this->craigslistAd = $craigslistAd;
  }
}

$presenter = VehicleOnCraigslistPresenter::present($vehicle, $vehicle->craiglistAd);
```
There's the minimum code for a presenter! (`present` is just some nice syntactic sugar that provides a more fluent way to instantiate your presenter. If it makes you nervous, you can always new it up like usual).

Ok... I can see that you're not very impressed yet. Let's do things with it!
#### Delegation
Delegation allow you to reach through your presenter to the models (or other objects) it contains while avoiding much of the boilerplate of typical getters.
```php
class VehicleOnCraigslistPresenter extends Presenter
{
  //...
  protected $delegatesTo = [
    'vehicle' => ['year', 'make'],
    'craigslistAd' => ['listedAt'],
  ];
  //...
}
```
Now we can "reach through" the presenter to access the delegates properties!
```php
$presenter = VehicleOnCraigslistPresenter::present($vehicle, $vehicle->craiglistAd);

$presenter->year; //$presenter->vehicle->year;
$presenter->make; //$presenter->vehicle->make;
$presenter->listedAt; // $presenter->craigslistAd->listedAt;
```
Ok, a little cooler, right? What if we need something more complicated?
#### Getters (Accessors)
I like using "getter" instead of "accessor" because I'm just not that fancy. The presenter has getters that work just like Laravel's Eloquent getters:
```php
class VehicleOnCraigslistPresenter extends Presenter
{
  //...
  public function getVehicleTitleAttribute()
  {
    return "{$this->vehicle->year} {$this->vehicle->make} {$this->vehicle->model}";
  }
  
  public function getCachedAtAttribute($currentValue)
  {
    return DateTime::createFromFormat('Y-m-d', $passedInDate)->format('l, M jS');
  }
  //...
}
```
We can now access this like any other property:
```php
$presenter->vehicleTitle;
//or if you prefer
$presenter->vehicle_title;
```
Both camelCase and snake_case work the same.
If you put information into your presenter manually, it will automatically be passed as an argument to your getter. 
```php
$presenter->cachedAt = '2017-09-21';
echo $presenter->cachedAt; //Thursday, Sept 21st
```
**Note:** Wish you had a better way to put data on your presenter? Just hang tight for the tap method below!

#### Setters (Mutators)
Technically, the presenters do have setters, but I haven't thought of a use case for them. They exist in order to aid the getters, but could be used standalone. I haven't thought of a real use case for them, but here's a quick example in case you do:
```php
public function setSomeExampleAttribute($value)
{
  $this->setAttribute(strtoupper($value));
}
```
Now you can:
```php
$presenter->someExample = 'my message';
echo $presenter->someExample; //MY MESSAGE
```
#### Tap
I'm a sucker for fluid syntax and avoiding temporary variables, so if you want to add some instance variables to your presenter when it's instantiated, you can do so with the tap method:
```php
$presenter = VehicleOnCraigslistPresenter::present($vehicle, $vehicle->craiglistAd)->tap(function ($presenter) {
  $presenter->cachedAt = date('Y-m-d');
});

echo $presenter->cachedAt; //Thursday, Sept 21st (don't forget our getter from above)
```
#### Json
I try to exclusively develop API endpoints to be consumed by my front-end, so my use cases for this are always json. If you're like me, then you'll be happy to know that you have three great ways to get json out of your presenter:
You can use the toJson method:
```php
echo $presenter->toJson();
```
You can simply use the php function json_encode (presenters implement `JsonSerializable`)
```php
echo json_encode($presenter);
```
OR if you use Laravel (and if you don't, you should strongly reconsider), you can just directly return presenters from any controller route and they'll be converted to json automatically! 
This is where the code really starts to get sexy:
```php
return VehicleOnCraigslistPresenter::present($vehicle, $vehicle->craiglistAd)->tap(function ($p) {
  $p->cachedAt = date('Y-m-d');
});
```

#### Wait, what is converted to JSON?
Presenters automatically turn all of their delegates, any getters, and any variables that were manually set (through tap or otherwise) as JSON.
Our json from the presenter we've been building will look something like this:
```javascript
{"year": 2015, "make": "Chevy", "listedAt": "2017-08-01", "vehicleTitle": "2015 Chevy Volt", "cachedAt": "Thursday, Sept 21st", "someExample": "MY MESSAGE"}
```

#### But hang on, I prefer my JSON to be snake_cased? Is there any hope for me?
Oh come on, how on earth would we manage to make something like.. OH WAIT ONE SECOND
```php
class VehicleOnCraigslistPresenter extends Presenter
{
  //...
  protected $jsonEncodeCase = 'snake';
  //...
}
```
Happy? Valid options are `camel`, `snake`, or (if you're an irredeemable monster), `studly`

## That's all for now! I hope to update as I begin using this in my projects more and would be irrationally thrilled to receive a pull request if you think you can improve it. 

