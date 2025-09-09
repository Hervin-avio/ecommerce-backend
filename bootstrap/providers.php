<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class, // <-- provider custom Fortify kamu
    Laravel\Fortify\FortifyServiceProvider::class, // <-- provider paket Fortify
];
