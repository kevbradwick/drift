<?php

namespace fixtures;

class Person
{
    /**
     * Drift\String()
     * @var string
     */
    private $firstName;

    /**
     * Drift\Int(field="age")
     * @var int
     */
    private $age;

    /**
     * This is an example of a complex property. It maps to another class.
     * The data itself will be an associative array.
     *
     * Drift\Entity(
     *      field="parent_mother",
     *      class="fixtures\Person"
     * )
     * @var Person
     */
    private $mother;

    /**
     * This will be ignored by the annotation reader
     * @var string
     */
    private $eyeColour;
}
