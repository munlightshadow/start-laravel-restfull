<?php


namespace App\Routing;

use Illuminate\Routing\ResourceRegistrar as ResourceRegistrarLaravel;

class ResourceRegistrar extends ResourceRegistrarLaravel
{
    protected $resourceDefaults = ['list', 'store', 'show', 'edit', 'update', 'destroy'];

    protected function addResourceList($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/list';

        $action = $this->getResourceAction($name, $controller, 'index', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceStore($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/store';

        $action = $this->getResourceAction($name, $controller, 'store', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceShow($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/show/{'.$base.'?}';
        
        $action = $this->getResourceAction($name, $controller, 'show', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceEdit($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/'.static::$verbs['edit'].'/{'.$base.'}';

        $action = $this->getResourceAction($name, $controller, 'edit', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/update/{'.$base.'}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceDestroy($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/destroy/{'.$base.'}';

        $action = $this->getResourceAction($name, $controller, 'destroy', $options);

        return $this->router->delete($uri, $action);
    }
}