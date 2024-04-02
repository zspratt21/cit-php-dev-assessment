<?php

declare(strict_types=1);

namespace App;

use RedBeanPHP\ToolBox;

class App extends \Minicli\App
{
    protected ToolBox $toolbox;

    public function setToolBox(ToolBox $toolbox): void
    {
        $this->toolbox = $toolbox;
    }

    public function getToolBox(): ToolBox
    {
        return $this->toolbox;
    }
}
