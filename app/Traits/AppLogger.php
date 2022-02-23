<?php

namespace App\Traits;

use App\Exceptions\RequestValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Session;

use App\Includes\Browser\Browser;
use App\Models\Logs\UserLog;

/**
 * Traits are a mechanism for code reuse in single inheritance languages such 
 * as PHP.  A Trait is intended to reduce some limitations of single 
 * inheritance by enabling a developer to reuse sets of methods freely in 
 * several independent classes living in different class hierarchies.
 */

trait AppLogger{
    
    /**
     * Set the user log
     *
     * @param array $logs
     * @return void
     */
    public function userLog($logs=array()) {
        
        $browser = new Browser();
        $browser_name = $browser->getBrowser();
        $version = $browser->getVersion();
        $platform = $browser->getPlatform();

        $browser_device = "unknown";
        if($browser->isAol())$browser_device = "AOL";
        if($browser->isMobile())$browser_device = "Mobile";
        if($browser->isTablet())$browser_device = "Tablet";
        if($browser->isRobot())$browser_device = "Robot";
        if($browser->isFacebook())$browser_device = "Facebook";

        $details = ip_details($logs['ip_address']);
        $country = null;
        if(isset($details->country))$country = $details->country;

        $log = new UserLog();
        $log->user_id = $logs['user_id'];
        $log->login_time = Carbon::now();
        $log->ip_address = (isset($logs['ip_address'])) ? $logs['ip_address'] : null;
        $log->remember_me = (isset($logs['remember_me'])) ? $logs['remember_me'] : null; 
        $log->browser_name = $browser_name;
        $log->version = $version;
        $log->platform = $platform;
        $log->browser_device = $browser_device;
        $log->country = $country;
        $log->save();
    }
}