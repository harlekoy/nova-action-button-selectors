<?php

namespace Lednerb\ActionButtonSelector;

trait ShowAsButton
{
    /**
     * ShowAsButton constructor.
     */
    public function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct();
        }

        return $this->showAsButton();
    }

    public function showAsButton($show = true)
    {
        return $this->withMeta(['showAsButton' => $show]);
    }
}
