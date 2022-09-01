<?php

namespace App\Http\Middleware;

class TeamsPermission
{
    public function handle($request, \Closure $next)
    {
        $user = $request->user();
        if (!empty($user)) {
            // session value set on login
            if($user->companies->count() > 0){
                // setPermissionsTeamId(1);
                setPermissionsTeamId($user->companies->first()->id);
            } else {
                // 1 = PT. Alba Digital Teknologi / DEFAULT SUPER ADMIN COMPANY
                setPermissionsTeamId(1);
            }
        }
        // other custom ways to get team_id
        /*if(!empty(auth('api')->user())){
            // `getTeamIdFromToken()` example of custom method for getting the set team_id
            setPermissionsTeamId(auth('api')->user()->getTeamIdFromToken());
        }*/

        return $next($request);
    }
}
