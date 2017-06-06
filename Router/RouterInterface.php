<?php

namespace BuildIt\Router;

/**
 * Interface RouterInterface
 * @package BuildIt\Router
 */
interface RouterInterface
{
    /**
     * @return mixed
     */
    function notFoundCallable();

    /**
     * @return mixed
     */
    function isUserAdminCallable();
}