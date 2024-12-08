<?php

namespace Tec\Base\Http\Controllers;

use Tec\ACL\Models\UserMeta;
use Illuminate\Http\Request;

class ToggleThemeModeController extends BaseController
{
    public function __invoke(Request $request)
    {
        $request->validate(['theme' => 'required|in:light,dark']);

        $themeMode = $request->query('theme');

        UserMeta::setMeta('theme_mode', $themeMode);

        return redirect()->back();
    }
}
