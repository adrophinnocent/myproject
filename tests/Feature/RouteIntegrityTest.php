<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteIntegrityTest extends TestCase
{
    public function test_every_controller_route_points_to_an_existing_method(): void
    {
        $broken = [];

        foreach (app('router')->getRoutes() as $route) {
            $action = $route->getActionName();

            if ($action === 'Closure' || ! str_contains($action, '@')) {
                continue;
            }

            [$class, $method] = explode('@', $action);

            if (! class_exists($class) || ! method_exists($class, $method)) {
                $broken[] = implode('|', $route->methods()).' /'.$route->uri().' -> '.$action;
            }
        }

        $this->assertSame([], $broken, "Routes point to missing controller actions:\n".implode("\n", $broken));
    }

    public function test_route_names_are_unique(): void
    {
        $seen = [];
        $duplicates = [];

        foreach (app('router')->getRoutes() as $route) {
            $name = $route->getName();

            if ($name === null) {
                continue;
            }

            if (isset($seen[$name])) {
                $duplicates[] = $name;
            }

            $seen[$name] = true;
        }

        $this->assertSame([], array_unique($duplicates));
    }
}
