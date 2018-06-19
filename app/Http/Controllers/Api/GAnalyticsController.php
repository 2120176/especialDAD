<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Analytics;
use Spatie\Analytics\Period;
use File;

class GAnalyticsController extends Controller
{
    public function statistics(){

    	$storagePath = storage_path().'/app/analytics/';
    	if (File::exists($storagePath . 'service-account-credentials.json')) {

    		//Last week visitors statistics
    		$month_visits = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30))->groupBy(function (array $visitorStatistics) {
    		    return $visitorStatistics['date']->format('Y-m-d');
    		})->map(function ($visitorStatistics, $yearMonth) {
    		    list($year, $month ,$day) = explode('-', $yearMonth);
    		    return ['date' => "{$year}-{$month}-{$day}", 'visitors' => $visitorStatistics->sum('visitors'), 'pageViews' => $visitorStatistics->sum('pageViews')];
    		})->values();

    		//yearly visitors statistics
    		$year_visits = Analytics::fetchTotalVisitorsAndPageViews(Period::days(365))->groupBy(function (array $visitorStatistics) {
    		    return $visitorStatistics['date']->format('Y-m');
    		})->map(function ($visitorStatistics, $yearMonth) {
    		    list($year, $month ) = explode('-', $yearMonth);
    		    return ['date' => "{$year}-{$month}", 'visitors' => $visitorStatistics->sum('visitors'), 'pageViews' => $visitorStatistics->sum('pageViews')];
    		})->values();

    		$ga = true;

    		

    	}else{

    		//Only for demo, feel free to perform some action if google analytics is not available

    		$year_visits = [["date"=>"2017-01","visitors"=>0,"pageViews"=>0],["date"=>"2017-02","visitors"=>0,"pageViews"=>0],["date"=>"2017-03","visitors"=>0,"pageViews"=>0],["date"=>"2017-04","visitors"=>0,"pageViews"=>0],["date"=>"2017-05","visitors"=>0,"pageViews"=>0],["date"=>"2017-06","visitors"=>0,"pageViews"=>0],["date"=>"2017-07","visitors"=>0,"pageViews"=>0],["date"=>"2017-08","visitors"=>1716,"pageViews"=>10340],["date"=>"2017-09","visitors"=>2854,"pageViews"=>15012],["date"=>"2017-10","visitors"=>2901,"pageViews"=>13654],["date"=>"2017-11","visitors"=>2294,"pageViews"=>11653],["date"=>"2017-12","visitors"=>1968,"pageViews"=>9183],["date"=>"2018-01","visitors"=>491,"pageViews"=>3201]];
    						
    		$month_visits = [["date"=>"2017-12-10","visitors"=>51,"pageViews"=>229],["date"=>"2017-12-11","visitors"=>86,"pageViews"=>461],["date"=>"2017-12-12","visitors"=>94,"pageViews"=>470],["date"=>"2017-12-13","visitors"=>60,"pageViews"=>234],["date"=>"2017-12-14","visitors"=>73,"pageViews"=>325],["date"=>"2017-12-15","visitors"=>66,"pageViews"=>316],["date"=>"2017-12-16","visitors"=>70,"pageViews"=>259],["date"=>"2017-12-17","visitors"=>60,"pageViews"=>308],["date"=>"2017-12-18","visitors"=>69,"pageViews"=>408],["date"=>"2017-12-19","visitors"=>86,"pageViews"=>255],["date"=>"2017-12-20","visitors"=>76,"pageViews"=>331],["date"=>"2017-12-21","visitors"=>71,"pageViews"=>287],["date"=>"2017-12-22","visitors"=>64,"pageViews"=>272],["date"=>"2017-12-23","visitors"=>47,"pageViews"=>211],["date"=>"2017-12-24","visitors"=>32,"pageViews"=>97],["date"=>"2017-12-25","visitors"=>33,"pageViews"=>128],["date"=>"2017-12-26","visitors"=>63,"pageViews"=>258],["date"=>"2017-12-27","visitors"=>59,"pageViews"=>291],["date"=>"2017-12-28","visitors"=>67,"pageViews"=>385],["date"=>"2017-12-29","visitors"=>71,"pageViews"=>522],["date"=>"2017-12-30","visitors"=>46,"pageViews"=>179],["date"=>"2017-12-31","visitors"=>28,"pageViews"=>184],["date"=>"2018-01-01","visitors"=>36,"pageViews"=>309],["date"=>"2018-01-02","visitors"=>66,"pageViews"=>444],["date"=>"2018-01-03","visitors"=>73,"pageViews"=>319],["date"=>"2018-01-04","visitors"=>84,"pageViews"=>666],["date"=>"2018-01-05","visitors"=>73,"pageViews"=>462],["date"=>"2018-01-06","visitors"=>59,"pageViews"=>250],["date"=>"2018-01-07","visitors"=>52,"pageViews"=>274],["date"=>"2018-01-08","visitors"=>48,"pageViews"=>477],["date"=>"2018-01-09","visitors"=>0,"pageViews"=>0]];

    		$ga = false;
    	}
    	return response()->json(array('year_visits'=>$year_visits, 'month_visits'=>$month_visits, 'ga' => $ga));
    }
}
