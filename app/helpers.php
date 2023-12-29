<?php


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

if (!function_exists('layoutConfig')) {
    function layoutConfig()
    {
        $__getConfiguration = Config::get('app-config.layout.vlm-rtl');

        if (Request::is('login')) {

            $__getConfiguration = Config::get('app-config.layout.vlm');

        } else {
            $__getConfiguration = Config::get('barebone-config.layout.bb');
        }

        return $__getConfiguration;
    }
}


if (!function_exists('getRouterValue')) {
    function getRouterValue(): string
    {
        $__getRoutingValue = '/rtl/modern-light-menu';

        if (Request::is('login')) {

            $__getRoutingValue = '/modern-light-menu';

        } else {
            $__getRoutingValue = '';
        }
        return $__getRoutingValue;
    }
}
