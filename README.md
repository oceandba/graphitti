<h2 align="center">
    Graphitti
</h2>

<p align="center">
    <a href="https://packagist.org/packages/oceandba/graphitti"><img src="https://poser.pugx.org/oceandba/graphitti/v/stable?format=flat-square" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/oceandba/graphitti"><img src="https://poser.pugx.org/oceandba/graphitti/v/unstable?format=flat-square" alt="Latest Unstable Version"></a>
    <a href="https://travis-ci.org/oceandba/graphitti"><img src="https://travis-ci.org/oceandba/graphitti.svg?branch=master" alt="Build Status"></a>
    <a href="https://github.styleci.io/repos/222423453"><img src="https://github.styleci.io/repos/222423453/shield?branch=master" alt="StyleCI"></a>
    <a href="https://packagist.org/packages/oceandba/graphitti"><img src="https://poser.pugx.org/oceandba/graphitti/license?format=flat-square" alt="License"></a>
    <a href="https://packagist.org/packages/oceandba/graphitti"><img src="https://poser.pugx.org/oceandba/graphitti/downloads?format=flat-square" alt="Total Downloads"></a>
</p>

## Introduction
The aim of Graphitti is to ease the use of the [Render URL API](https://graphite.readthedocs.io/en/latest/render_api.html) provided by graphite.
Graphitti provides a expressive fluid API around the Render URL API.

## License
Graphitti is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Installation
This packages works for Laravel versions 5.* and ^6.0 only.

Install Graphitti as you would with any other dependency managed by Composer:

```bash
composer require oceandba/graphitti
```

### Configuration

To publish the configuration use the command :

```bash
php artisan vendor:publish --provider="OceanDBA\Graphitti\GraphittiServiceProvider" --tag="graphitti-config"
```

This will create the file ```config/graphitti.php```. Use this file to configure your graphite hosts; the file should be self-explanatory and 
contains the appropriate configurations needed for you to get started.

### Usage

### Using Targets

You can create a new [Target](https://graphite.readthedocs.io/en/latest/render_api.html#target) using the ```OceanDBA\Graphitti\Metrics\Target``` class as
follows :

```php
use OceanDBA\Graphitti\Metrics\Target;

$target = Target::make('oceandba.server1.load', 'Server-load-1');
```

The ```Target``` takes two parameters :
1) The path
2) An optional name for the Target

### Applying functions to Target

To apply a function to the ```Target``` use the function name as a method call on the Target instance and pass parameters to it :

```php
Target::make('oceandba.server1.load', 'Server-load-1')->add(10);
```

You can also chain the function calls :

```php
Target::make('oceandba.server1.load', 'Server-load-1')
      ->add(10)
      ->aggregate('sum');
```

Refer to the [Graphite Docs](https://graphite.readthedocs.io/en/latest/functions.html) for list of available functions.

The ```Target``` class uses the ```Macroable``` trait of Laravel. So you are free to add your own methods to the class.

```php
use OceanDBA\Graphitti\Metrics\Target;

// ...

public function boot()
{
    Target::macro('validate', function () {
        if(strpos($this->value(), 'oceandba') === false) {
            throw new \InvalidArgumentException('Target is invalid');
        }
        
        return $this;
    });
}
```

```php
Target::make('oceandba.server1.load', 'Server-load-1')->validate();
```

### Using GraphitePoints

The ```GraphitePoints``` class provides an expressive syntax to retrieve DataPoints from Graphite. You should supply at least one ```Target``` to the 
```GraphitePoints```.

```php
use GraphitePoints;
use OceanDBA\Graphitti\Metrics\Target;

$dataPointsCollection = GraphitePoints::addTarget(Target::make('oceandba.server1.load', 'Server-load-1'))
                                      ->addTarget(Target::make('oceandba.server2.load', 'Server-load-2'))
                                      ->render();
```

This will return a ```OceanDBA\Graphitti\Series\DataPointsCollection```. The object contains a collection of ```OceanDBA\Graphitti\Series\DataPoints```,
both classes are ```Macroable```.

### Contraint from/until

You can add time period constrains using the ```from``` and ```until``` methods available on the ```GraphitePoints```.

```php
 GraphitePoints::addTarget(Target::make('oceandba.server1.load', 'Server-load-1'))
               ->addTarget(Target::make('oceandba.server2.load', 'Server-load-2'))
               ->from('-1h')
               ->until('now')
               ->render()
```

The methods accept ```Carbon``` instances as well as relative time string. Refer to the [Graphite Docs](https://graphite.readthedocs.io/en/latest/render_api.html#from-until)
for how to form relative time string. 

### Additional graph parameters

You can add graph parameters using ```addParameter``` method on ```GraphitePoints``` :

```php
GraphitePoints::addTarget(Target::make('oceandba.server1.load', 'Server-load-1'))
               ->addTarget(Target::make('oceandba.server2.load', 'Server-load-2'))
               ->from('-1h')
               ->until('now')
               ->addParameter('maxDataPoints', 50)
               ->render()
```

Refer to the [Graphite Docs](https://graphite.readthedocs.io/en/latest/render_api.html#graph-parameters) for full list of accepted parameters.

### Credits
Big Thanks to all developers who worked hard to create something amazing!

### Creator
[![OceanDBA Ltd](https://img.shields.io/badge/Author-OceanDBA-blue.svg)](https://www.oceandba.com/)

Twitter: [@OceanDBA](https://twitter.com/oceandba)
<br/>
GitHub: [OceanDBA Ltd](https://github.com/oceandba)
