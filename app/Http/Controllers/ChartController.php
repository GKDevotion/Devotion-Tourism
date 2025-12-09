<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Industry;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
    }

    /**
     * Generate Industry base chart data
     */
    public function generateIndustryBaseChartData(){

        $monthArr = [];
        $response = [];
        for ($i=1; $i<=12; $i++) {
            $response['labels'][] = date( "M Y", strtotime( "-".( $i - 12 )." months" ) );
            $monthArr[] = date( "Y-m", strtotime( "-".( $i - 12 )." months" ) );
        }

        $industryObjs = Industry::with('user')->select('id', 'name', 'rgb_code')->where( ['status' => 1, 'type' => 1 ])->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->get()->toArray();

        foreach( $industryObjs as $k=>$ind ){
            $response['datasets'][$k]['label'] = $ind['name'];
            $response['datasets'][$k]['fill'] = false;
            $response['datasets'][$k]['borderColor'] = "rgba(".$ind['rgb_code'].", 1)";
            $response['datasets'][$k]['pointBackgroundColor'] = "rgba(".$ind['rgb_code'].")";
            foreach( $monthArr as $month ){
                $dataObj = Client::with('user')->selectRaw('joining_date, MONTH(joining_date) as month, YEAR(joining_date) as year, COUNT(id) as total')
                        ->where('joining_date', '>=', $month.'-01')
                        ->where('joining_date', '<=', $month.'-31')
                        ->where('industry_id', $ind['id'])
                        ->groupBy('joining_date', 'month', 'year')
                        ->groupBy('joining_date') // Group by category for better chart preparation
                        ->get()
                        ->toArray();

                $calc = 0;
                if( COUNT( $dataObj ) > 0 ){
                    foreach( $dataObj as $data ){
                        $calc+= $data['total'];
                    }
                }

                $response['datasets'][$k]['data'][] = $calc;
            }
        }

        return response()->json( $response, 200 );
    }

    /**
     * Generate Industry base chart data
     */
    public function generateContinentBaseChartData(){

        $monthArr = [];
        $response = [];
        for ($i=1; $i<=12; $i++) {
            $response['labels'][] = date( "M Y", strtotime( "-".( $i - 12 )." months" ) );
            $monthArr[] = date( "Y-m", strtotime( "-".( $i - 12 )." months" ) );
        }

        
        $contientObjs = Continent::select('id', 'name')->where( ['status' => 1])->orderBy('name', 'ASC')->get()->toArray();
        foreach( $contientObjs as $k=>$ind ){
            $response['datasets'][$k]['label'] = $ind['name'];
            $response['datasets'][$k]['fill'] = false;
            $hax_code = generateRandomHexColor();
            $response['datasets'][$k]['borderColor'] = "rgba(".hexToRgb( $hax_code ).", 1)";
            $response['datasets'][$k]['pointBackgroundColor'] = "rgba(".hexToRgb( $hax_code ).")";
            foreach( $monthArr as $month ){
                $dataObj = Client::with('user')->selectRaw('continent_id, MONTH(joining_date) as month, YEAR(joining_date) as year, COUNT(id) as total')
                        ->where('joining_date', '>=', $month.'-01')
                        ->where('joining_date', '<=', $month.'-31')
                        ->where('continent_id', $ind['id'])
                        ->groupBy('continent_id', 'month', 'year')
                        ->groupBy('continent_id') // Group by category for better chart preparation
                        ->get()
                        ->toArray();

                $calc = 0;
                if( COUNT( $dataObj ) > 0 ){
                    foreach( $dataObj as $data ){
                        $calc+= $data['total'];
                    }
                }

                $response['datasets'][$k]['data'][] = $calc;
            }
        }

        return response()->json( $response, 200 );
    }

    /**
     * company base admin chart data
     */
    public function companyBaseAdminChartData( Request $request){
        return getCompanyBaseAdminRecords( $request );
    }

    /**
     * 
     */
    public function getDashboardNotifications(){

        $notificationObj = Notification::where( 'status', 0 )->get();
        $response = [];

        foreach( $notificationObj as $k=>$ar ){
            $response[$k]['id'] = $ar->id;
            $response[$k]['title'] = $ar->title;
            $response[$k]['url'] = url('/');
            $response[$k]['time'] = getTimeDifference($ar->created_at);
        }

        return response()->json( $response, 200 );
    }
}
