<?php
// PWS#7-2

return [
    'master' => [
      'relazioni' => [
        'generi'            => 0,
        'sottogeneri'       => 1,
        'countries'         => 2,
        'registi'           => 3,
        'sceneggiatori'     => 4,
        'compositori'       => 5,
        'casaproduzione'    => 6,
        'attori'            => 7, // PWS#10-lang
        'lingua'            => 8 // PWS#10-lang
      ],
      'generi'  => [
        'animazione'        => 3,
        'azione'            => 5, // PWS#18
        'commedia'          => 9, // PWS#18
        'documentario'      => 11,
        'fantascienza'      => 14, // PWS#18
        'film_per_adulti'   => 33, // PWS#15
        'thriller'          => 29, // PWS#18
      ]
    ],
    'release' => [
      'tipo' => [
        'poster' => 1, // PWS#02-23
      ],
      'relazioni' => [
        'lingua' => 1, // PWS#chiusura
      ]
    ]
];
