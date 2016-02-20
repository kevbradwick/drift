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

    /**
     * @Drift\Strup()
     * @var string
     */
    private $genre;

    /**
     * @Drift\Int()
     * @var int
     */
    private $duration = 120;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function getCast()
    {
        return $this->cast;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
