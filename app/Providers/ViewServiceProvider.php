<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use DOMDocument;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $xmlPath = storage_path('app/datos.xml');
            $datos = [];

            if (file_exists($xmlPath)) {
                $xml = new DOMDocument();
                $xml->load($xmlPath);

                foreach ($xml->documentElement->childNodes as $nodo) {
                    if ($nodo->nodeType === XML_ELEMENT_NODE) {
                        $datos[$nodo->nodeName] = $nodo->nodeValue;
                    }
                }
            }

            $view->with('datosC', $datos);
        });
    }
}
