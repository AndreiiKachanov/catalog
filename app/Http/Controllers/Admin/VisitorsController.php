<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart\Token;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class VisitorsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $tokens = Token::orderByDesc('created_at')
            ->paginate(config('app.pagination_default_value'));
        $countAllUsers = Token::count();
        $countMobileUsers = Token::where('is_mobile', true)->count();
        $countDesktopUsers = Token::where('is_desktop', true)->count();
        return view('admin.visitors.index', compact('tokens', 'countMobileUsers', 'countDesktopUsers', 'countAllUsers'));
    }
}
