<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ZbsMembers;
use App\Models\VisitLogs;


class Portal
{
    public function handle(Request $request, Closure $next)
    {

        $ismember = ZbsMembers::where('member_id', $request->member_id)->get();
        if(!$ismember->count())
        {
            return redirect('https://zbsburial.com/portal');
        }
        else{
          $log=new VisitLogs();
          $log->member_id=$request->member_id;
          $log->ipaddr=request()->ip();
          $log->save();
          return $next($request);
        }



    }
   
}
