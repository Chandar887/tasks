<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class TestController extends Controller
{
    public function index()
    {
        echo '<br>Time zone is: ' . date('e');
        echo '<Br>Time zone is: ' . date_default_timezone_get();
        echo "<Br>The time is " . date("h:i:sa");
        return;
        // $bets = Bet::select('user_id')
        //     ->selectRaw('sum(amount) as total_amount')
        //     ->with('user')
        //     ->groupBy(function ($d) {
        //         return Carbon::parse($d->created_at)->format('m');
        //     })
        //     // ->groupBy('user_id')
        $bets = Bet::
            // select('user_id')
            selectRaw('sum(amount) as total_amount')
            ->selectRaw("DATE_FORMAT(created_at,'%M %Y') as months")
            ->selectRaw('max(created_at) as createdAt')

            //    ->where("created_at", ">", \Carbon\Carbon::now()->subMonths(6))
            ->orderBy('createdAt', 'desc')
            ->groupBy('months')
            ->get();


        return $bets;

        $bet = Bet::with('user', 'category')->get();
        return $bet;

        $user = auth()->user();
        return $user->bets;

        // $agent = new Agent();
        // $deviceData = [
        //     'id' => uniqid(),
        //     'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
        //     'device' => $agent->device(),
        //     'os' => $platform = $agent->platform(),
        //     'os_version' => $agent->version($platform),
        //     'browser' => $agent->browser(),
        //     'robot ' => $agent->robot(),
        // ];

        // $device_token =  Cookie::get('device_token');
        // if ($device_token==null) {
        //     $device_token = md5(Crypt::encryptString(implode("*", $deviceData)));
        //     Cookie::make('device_token', $device_token,50000);
        //     dd($device_token);
        // }
        // return $device_token;


        // $value = Cache::rememberForever('device_id', function () use($deviceData) {
        //     return md5(Crypt::encryptString(implode("*", $deviceData)));
        // });
    }
}
