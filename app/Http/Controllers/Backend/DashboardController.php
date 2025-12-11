<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountField;
use App\Models\Admin;
use App\Models\Company;
use App\Models\CompanyAdminMap;
use App\Models\Currency;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if( is_null($this->user) ){
            return redirect('admin/login');
        }

        // if ( is_null($this->user) ) {
        //     abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        // }

        $auth = $this->user;

        return view('backend.pages.dashboard.index');
    }

    /**
     *
     */
    public function totalBadgeCount( $auth, $companyIdArr, $where=[] )
    {
        // return Cache::remember('totalCountBadge', 10, function () use ($where) {

        $companies = Company::select( 'id')
        ->where( [
            'status' => 1
        ] );

        if( $auth->admin_user_group_id != 1 ){
            $companies->whereIn( 'id', $companyIdArr );
        }

            $totalCountBadge = [
                'totalCompanies' => $companies->count(),
                'totalUsers' => Admin::select('id')->count(),
                'totalCurrency' => Currency::select('id')->where('status', 1)->count(),
                'totalActiveInquiry' => Inquiry::select('id')->where('status',1)->count(),
            ];

            return $totalCountBadge;
        // });
    }

}
