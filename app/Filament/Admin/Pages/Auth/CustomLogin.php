<?php

namespace App\Filament\Admin\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    protected static string $view = 'filament.admin.pages.auth.custom-login';
    protected static string $layout = 'filament.admin.layouts.auth';
}
