<?php 

/**
 * ip_location class
 *
 * @package munkireport
 * @author 
 **/
class Ip_location_controller extends Module_controller
{
	    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }
	
    /**
     * Get ip_location information for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {
        $result = Ip_location_model::select('ip_location.*')
        ->whereSerialNumber($serial_number)
        ->filter()
        ->limit(1)
        ->first();
        if ($result) {
            jsonView($result->toArray());
        } else {
            jsonView([]);
        }
    }

    public function get_list($column = '')
    {
        jsonView(
            Ip_location_model::select("ip_location.$column AS label")
                ->selectRaw('count(*) AS count')
                ->filter()
                ->whereRaw("ip_location.$column IS NOT NULL AND ip_location.$column <> ''")
                ->groupBy($column)
                ->orderBy('count', 'desc')
                ->get()
                ->toArray()
        );
    }
} 