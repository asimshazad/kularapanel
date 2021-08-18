<?php

namespace asimshazad\simplepanel;

class Widget
{
	public static function __callStatic($name, $arguments)
    {
		if (count($arguments)) {
			return (new \ReflectionMethod(app(config('asimshazad.widgets_namespace') . '\\' . studly_case($name).'Widget'), 'handle'))
				->invokeArgs(app(config('asimshazad.widgets_namespace') . '\\' . studly_case($name).'Widget'), $arguments);
		}
		return app(config('asimshazad.widgets_namespace') . '\\' . studly_case($name).'Widget')->handle();
    }
}
