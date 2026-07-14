<?php

namespace App\Support;

class MagazineLayout
{
    public static function usesMagazineChrome(): bool
    {
        if (! request()->routeIs('landing.show')) {
            return true;
        }

        $template = (string) request()->attributes->get('landing_template', 'template1');

        return in_array($template, ['template1', 'template2', 'template3'], true);
    }
}
