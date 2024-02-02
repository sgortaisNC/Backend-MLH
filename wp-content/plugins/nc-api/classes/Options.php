<?php

/**
 * Class OffreEmploi
 */
class Options
{
    public function getAll(): array
    {
        $options = get_fields('option');
        return $options;

    }
}