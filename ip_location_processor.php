<?php

use munkireport\processors\Processor;

class Ip_location_processor extends Processor
{
    public function run($data)
    {
        $modelData = ['serial_number' => $this->serial_number];

		// Parse data
        $sep = ' = ';
		foreach(explode(PHP_EOL, $data) as $line) {
            if($line){
                $parts = explode($sep, $line, 2); // Limit to 2 parts to handle values with = in them
                if (count($parts) == 2) {
                    list($key, $val) = $parts;
                    $modelData[$key] = $val;
                }
            }
		} //end foreach explode lines

        Ip_location_model::updateOrCreate(
            ['serial_number' => $this->serial_number], $modelData
        );
        
        return $this;
    }   
}
