<?php

namespace BuildIt\Controller;

/**
 * Class Controller
 * @package BuildIt\Controller
 */
abstract class Controller
{
    /**
     * @var string $templatesPath
     * Folder Path where all templates are stored.
     */
    protected $templatesPath = null;

    /**
     * @var string $viewsPath
     * Folder Path where all views are stored.
     */
    protected $viewsPath = null;

    /**
     * Renders your controller action in a $view which is inside a $template.
     * @param string $template
     * @param string $view
     * @param array $params
     */
    public function render($template, $view, $params = [])
    {
        ob_start();
        require $this->viewsPath . '/' . str_replace('.',  '/', $view) . '.php';
        $content = ob_get_clean();
        require $this->templatesPath . '/' . $template . '.php';
    }
}