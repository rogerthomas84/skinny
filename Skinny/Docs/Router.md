# \Skinny\Router #

## Usage ##

```php
$routes = array(
    'index' => array(
        'method' => 'GET',
        'path' => '/',
    ),
    'index_name' => array(
        'method' => 'GET',
        'path' => '/user/{name}',
        'params' => array(
           'ab' => 12
        )
    ),
    'index_post' => array(
        'method' => 'POST|PUT|DELETE',
        'path' => '/',
    ),
    'index_post_name' => array(
        'method' => 'POST|PUT|DELETE',
        'path' => '/user/{name}',
    )
);

$router = new \Skinny\Router();
$router->setRoutes($routes);

$router->setMethod('GET');
$router->setPath('/');
var_dump($router->getRoute());

$router->setMethod('GET');
$router->setPath('/user/joe');
var_dump($router->getRoute());


```