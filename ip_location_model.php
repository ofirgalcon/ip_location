<?php

use munkireport\models\MRModel as Eloquent;

class Ip_location_model extends Eloquent
{
    protected $table = 'ip_location';

    protected $hidden = ['id', 'serial_number'];

    protected $fillable = [
      'serial_number',
      'ip',
      'hostname',
      'city',
      'region',
      'country',
      'location',
      'organization',
      'postal_code',
      'timezone',

    ];
}
