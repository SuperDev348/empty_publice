<?php

namespace App\Http\Controllers\API;

use App\Libraries\AllSettingsFormat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator, Hash, Mail;
use DB;

class UserController extends Controller
{
    public $successStatus = 200;

    public function disableEnableUser(Request $request, $id)
    {
        $user = User::findOrFail($id);


        if ($user->disabled == 0) {
            $user->disabled = 1;
            $user->save();

            $response = [
                'message' => Lang::get('lang.user_enable_successfully'),
                'user' => $user
            ];

        } else {
            $user->disabled = 0;
            $user->save();

            $response = [
                'message' => Lang::get('lang.user_disable_successfully'),
                'user' => $user
            ];
        }

        return response()->json($response, 200);
    }

    public function details()
    {
        $user = Auth::user();

        return response()->json(['success' => $user], $this->successStatus);
    }

    public function getUserList(Request $request)
    {
        return User::getUserLists($request);
    }

    
    public function getUserWithVendor($id)
    {
        return User::userWithVendor($id);
    }


    public function getUserWithRole($id)
    {
        return User::userWithRole($id);
    }

    public function userBookingList(Request $request, $userid)
    {

        $clientBookingList = User::getUserBookingList($request, $userid);

        $allClientBookingList = $clientBookingList['data'];
        $settingAll = new AllSettingsFormat;
        foreach ($allClientBookingList as $value) {

            if ($value->booking_date) {
                $value->booking_date = $settingAll->getDate($value->booking_date);
            }

            if ($value->booking_time) {
                $value->booking_time = $settingAll->timeFormat(unserialize($value->booking_time));
            }
        }
        return ['datarows' => $allClientBookingList, 'count' => $clientBookingList['count']];
    }

    public function userDetails($id)
    {
        $settingAll = new AllSettingsFormat;
        $user = User::select('id', 'first_name', 'last_name', 'email', 'phone', 'avatar')->find($id);
        $user->date_of_birth = $settingAll->getDate($user->date_of_birth);

        return view('users.view', ['user' => $user]);
    }

    public function updates(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|max:255',
            'gender' => 'required',
            'role_id' => 'required'
        ]);

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $gender = $request->input('gender');
        $role_id = $request->input('role_id');

        $user = User::with('schedules')->findOrFail($id);
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->gender = $gender;
        $user->role_id = $role_id;

        if (!$user->update()) {

            return response()->json([
                'message' => Lang::get('lang.error_update')], 404);
        }

        $response = [
            'message' => Lang::get('lang.update_successful'),
            'user' => $user
        ];

        return response()->json($response, 200);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $isExistsInServices = User::join('services', 'services.created_by', '=', 'users.id')->where('created_by', $id)->first();
        $isExistsInBooking = User::join('bookings', 'bookings.user_id', '=', 'users.id')->where('user_id', $id)->first();
        $isExistsInInvoice = User::join('invoices', 'invoices.user_id', '=', 'users.id')->where('user_id', $id)->first();
        $isExistsInHolidaye = User::join('holidays', 'holidays.created_by', '=', 'users.id')->where('created_by', $id)->first();
        $isExistsInOauthClients = User::join('oauth_clients', 'oauth_clients.user_id', '=', 'users.id')->where('user_id', $id)->first();
        $isExistsInPayments = User::join('payments', 'payments.created_by', '=', 'users.id')->where('created_by', $id)->first();
        $isExistsInRoles = User::join('roles', 'roles.created_by', '=', 'users.id')->where('created_by', $id)->first();
        $isExistsInSessions = User::join('sessions', 'sessions.user_id', '=', 'users.id')->where('user_id', $id)->first();

        if ($isExistsInServices == null && $isExistsInBooking == null && $isExistsInInvoice == null && $isExistsInHolidaye == null && $isExistsInOauthClients == null && $isExistsInPayments == null && $isExistsInRoles == null && $isExistsInSessions == null) {
            $user->delete();
            $response = [
                'msg' => Lang::get('lang.user_has_been_deleted_successfully')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.user_in_use')
            ];
            return response()->json($response, 200);
        }
    }
}
