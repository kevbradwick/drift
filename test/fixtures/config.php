<?php

return [
   'fixtures\Movie' => [
       'title' => [
           'type' => 'string',
           'field' => 'movie_title'
       ],
       'releaseDate' => [
           'type' => 'date',
           'field' => 'release_date',
           'options' => [
               'format' => 'Y-m-d'
           ],
       ],
       'rating' => [
           'type' => 'string',
       ]
   ],
];
