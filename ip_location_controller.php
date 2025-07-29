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
        try {
            // Sanitize input
            $column = preg_replace("/[^A-Za-z0-9_\-]+/", '', $column);
            
            // Whitelist allowed columns to prevent column injection
            $allowed_columns = [
                'ip', 'hostname', 'city', 'region', 'country', 'location',
                'organization', 'postal_code', 'timezone'
            ];
            
            if (!in_array($column, $allowed_columns)) {
                jsonView([['label' => "Column '$column' rejected", 'count' => 0]]);
                return;
            }
            
            // Use the model with filter() like other working controllers
            jsonView(
                Ip_location_model::selectRaw("$column AS label, count(*) AS count")
                    ->whereNotNull($column)
                    ->where($column, '<>', '')
                    ->filter()
                    ->groupBy($column)
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get()
                    ->toArray()
            );
        } catch (Exception $e) {
            jsonView([['label' => 'Error: ' . $e->getMessage(), 'count' => 0]]);
        }
    }
} 