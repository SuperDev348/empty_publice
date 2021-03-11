<?php

namespace App\Http\Controllers\API;

use App\Libraries\CommonHelper;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        //vendordashboard.blade.php
        if (Auth::user()->is_admin == 0 && Auth::user()->role_id == 0 && Auth::user()->vendor_id == 0 ) {
            return view('dashboard.clientdashboard');

        }  else {

            return view('dashboard.dashboard');
        }
    }

    public function getData()
    {
        $businessType = CommonHelper::getBusinessType();

        $day = date('d');
        $month = date("m");
        $nextMonth = (int)$month + 1;
        $lastMonth = (int)$month - 1;
        $year = date("Y");

        if(Auth::user()->is_admin){
            $totalAllBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->whereBetween('bookings.booking_date', array($year . '-' . $month . '-' . $day, $year . '-' . $nextMonth . '-' . $day))->whereIn('bookings.status', ['confirmed','pending'])->count();
            $totalBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->whereBetween('bookings.booking_date', array($year . '-' . $month . '-' . $day, $year . '-' . $nextMonth . '-' . $day))->count();
            $todaysBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->whereDate('bookings.booking_date', date('Y-m-d'))->where('bookings.status', 'confirmed')->count();
            $curMonthTotBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->whereBetween('bookings.booking_date', array($year . '-' . $month . '-' . 01, $year . '-' . $month . '-' . 31))->count();
            $lastMonthTotBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->whereBetween('bookings.booking_date', array($year . '-' . $lastMonth . '-' . 01, $year . '-' . $lastMonth . '-' . 31))->count();
            $allTimeTotBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->count();
            $todaysBookingPending = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.business_type', $businessType)->whereDate('bookings.booking_date', date('Y-m-d'))->where('bookings.status', 'pending')->count();
                
            }
            else{
         $vendor_id = Auth::user()->vendor_id;
         error_log('$vendor_idxxxxxxxxxxxxxxxxxxxxx '.$vendor_id);
        $totalAllBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->whereBetween('bookings.booking_date', array($year . '-' . $month . '-' . $day, $year . '-' . $nextMonth . '-' . $day))->whereIn('bookings.status', ['confirmed','pending'])->count();
        $totalBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->whereBetween('bookings.booking_date', array($year . '-' . $month . '-' . $day, $year . '-' . $nextMonth . '-' . $day))->count();
        $todaysBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->whereDate('bookings.booking_date', date('Y-m-d'))->where('bookings.status', 'confirmed')->count();
        $curMonthTotBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->whereBetween('bookings.booking_date', array($year . '-' . $month . '-' . 01, $year . '-' . $month . '-' . 31))->count();
        $lastMonthTotBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->whereBetween('bookings.booking_date', array($year . '-' . $lastMonth . '-' . 01, $year . '-' . $lastMonth . '-' . 31))->count();
        $allTimeTotBooking = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->where('bookings.status', 'confirmed')->count();
        $todaysBookingPending = Booking::join('services', 'services.id', 'bookings.service_id')->where('services.vendor_id', $vendor_id)->where('services.business_type', $businessType)->whereDate('bookings.booking_date', date('Y-m-d'))->where('bookings.status', 'pending')->count();
            }
        //Line chart data
        $lineChartData = $this->lineChartData();

        //DoughnutData chart data
        $doughnutChartData = $this->doughnutChartData();

        return ['totalAllBooking' => $totalAllBooking,
            'totalBooking' => $totalBooking,
            'todaysBooking' => $todaysBooking,
            'todaysBookingPending' => $todaysBookingPending,
            'curMonthTotBooking' => $curMonthTotBooking,
            'lastMonthTotBooking' => $lastMonthTotBooking,
            'allTimeTotBooking' => $allTimeTotBooking,
            'lineChartData' => $lineChartData,
            'doughnutChartData' => $doughnutChartData
        ];
    }

    public function lineChartData()
    {
        $year = date("Y");
        $businessType = CommonHelper::getBusinessType();
        if(Auth::user()->is_admin){
        $bookingData = Booking::join('services', 'services.id', 'bookings.service_id')->groupBy(DB::raw('month(bookings.booking_date)'))
            ->where('services.business_type', $businessType)
            ->whereBetween('bookings.booking_date', array($year . '-01-01', $year . '-12-31'))
            ->where('bookings.status', '=', 'confirmed')
            ->select(DB::raw('COUNT(bookings.booking_date) as count'), DB::raw('month(bookings.booking_date) as month'))
            ->get();
        }else{
            $vendor_id = Auth::user()->vendor_id;
            $bookingData = Booking::join('services', 'services.id', 'bookings.service_id')->groupBy(DB::raw('month(bookings.booking_date)'))
            ->where('services.business_type', $businessType)
            ->where('services.vendor_id', $vendor_id)
            ->whereBetween('bookings.booking_date', array($year . '-01-01', $year . '-12-31'))
            ->where('bookings.status', '=', 'confirmed')
            ->select(DB::raw('COUNT(bookings.booking_date) as count'), DB::raw('month(bookings.booking_date) as month'))
            ->get();
        }

        $monthlyArray = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

        foreach ($bookingData as $data) {

            $monthlyArray[$data->month - 1] = $data->count;
        }

        return ['monthlyArray' => $monthlyArray];
    }

    public function doughnutChartData()
    {
        $date = Carbon::today()->toDateString();
        $businessType = CommonHelper::getBusinessType();
        if(Auth::user()->is_admin){
        $confirmedCount = Booking::join('services', 'services.id', 'bookings.service_id')
            ->where('services.business_type', $businessType)
            ->where('bookings.booking_date', '=', $date)
            ->where('bookings.status', '=', 'confirmed')
            ->count();

        $pendingCount = Booking::join('services', 'services.id', 'bookings.service_id')
            ->where('services.business_type', $businessType)
            ->where('bookings.booking_date', '=', $date)
            ->where('bookings.status', '=', 'pending')
            ->count();
        }else{
            $vendor_id = Auth::user()->vendor_id;
            $confirmedCount = Booking::join('services', 'services.id', 'bookings.service_id')
            ->where('services.vendor_id', $vendor_id)
            ->where('services.business_type', $businessType)
            ->where('bookings.booking_date', '=', $date)
            ->where('bookings.status', '=', 'confirmed')
            ->count();

        $pendingCount = Booking::join('services', 'services.id', 'bookings.service_id')
            ->where('services.vendor_id', $vendor_id)
            ->where('services.business_type', $businessType)
            ->where('bookings.booking_date', '=', $date)
            ->where('bookings.status', '=', 'pending')
            ->count();

        }

        return ['confirmedCount' => $confirmedCount, 'pendingCount' => $pendingCount];
    }
}
