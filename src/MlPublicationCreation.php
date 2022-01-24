<?php

namespace Aliraza371\MlPublicationCreation;

use Laravel\Nova\ResourceTool;

class MlPublicationCreation extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Ml_Publication_Creation';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'ml_publication_creation';
    }

    public function setMLAId($mlaId)
    {
        $this->withMeta(['mlaId' => $mlaId]);

        return $this;
    }
}
