<?php

return [
    [
        'key'        => 'cinema',
        'name'       => 'Cinema',
        'route'      => 'admin.cinema.master.index',
        'sort'       => 2,
        'icon-class' => 'release-icon',
    ],
    [
        'key'        => 'cinema.release',
        'name'       => 'Release',
        'route'      => 'admin.cinema.release.index',
        'sort'       => 3,
        'icon-class' => '',
    ] ,
    [
        'key'        => 'cinema.master',
        'name'       => 'Master',
        'route'      => 'admin.cinema.master.index',
        'sort'       => 3,
        'icon-class' => '',
    ], 
    [
    //     'key'        => 'cinema',
    //     'name'       => 'cinema',
    //     'route' => 'admin.cinema.index',
    //     'sort'       => 2,
    //     'icon-class' => '',
    // ] ,[
        'key'        => 'cinema.lingue',
        'name'       => 'Lingue',
        'route'      => 'admin.cinema.lingua.index',
       //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ],[
        'key'        => 'cinema.generi',
        'name'       => 'Generi',
        // 'route'      => 'admin.film.genere.index',
        'route' => 'admin.cinema.genere.index',
        'sort'       => 3,
        'icon-class' => '',
    ],[
        'key'        => 'cinema.subgeneri',
        'name'       => 'Sub-Generi',
        'route'      => 'admin.cinema.subgenere.index',
        //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ],[
        'key'        => 'cinema.actory',
        'name'       => 'Actory',
        'route'      => 'admin.cinema.attore.index',
        //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ], [
        'key'        => 'cinema.sceneggiatori',
        'name'       => 'Sceneggiatori',
        'route'      => 'admin.cinema.sceneggiatore.index',
        //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ],[
        'key'        => 'cinema.registi',
        'name'       => 'Registi',
        'route'      => 'admin.cinema.regista.index',
        //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ],[
        'key'        => 'cinema.compositori',
        'name'       => 'Compositoriory',
        'route'      => 'admin.cinema.compositore.index',
        //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ], [
        'key'        => 'cinema.produzione',
        'name'       => 'Case di Produzione',
        'route'      => 'admin.cinema.casaproduzione.index',
        //'route' => 'admin.cinema.index',
        'sort'       => 3,
        'icon-class' => '',
    ]
];