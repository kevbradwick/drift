<?php

namespace fixtures;

class Movie
{
    /**
     * @Drift\String()
     * @var string
     */
    private $title;

    /**
     * @Drift\Date(
     *     format="Y-m-d",
     *     field="release_date"
     * )
     * @var \DateTime
     */
    private $releaseDate;

    /**
     * @Drift\Array(field="film_rating", delimiter="-")
     * @var string
     */
    private $rating;

    private $cast;
}
