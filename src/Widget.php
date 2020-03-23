<?php

namespace Khludev\KuLaraPanel;

class Widget
{
	public static function __callStatic($name, $arguments)
    {
		if (count($arguments)) {
			return (new \ReflectionMethod(app(config('lap.widgets_namespace') . '\\' . studly_case($name).'Widget'), 'handle'))
				->invokeArgs(app(config('lap.widgets_namespace') . '\\' . studly_case($name).'Widget'), $arguments);
		}
		return app(config('lap.widgets_namespace') . '\\' . studly_case($name).'Widget')->handle();
    }
}
