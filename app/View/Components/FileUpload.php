<?php

namespace App\View\Components;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileUpload extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $allowedFiles;
    public $fileType;
    public $fileSize;
    public $preview;

    public function __construct(bool $allowedFiles=false, string $fileType='', string $fileSize='', bool $preview=false)
    {
        $this->allowedFiles = $allowedFiles;
        $this->fileType = $fileType;
        $this->fileSize = $fileSize;
        $this->preview = $preview;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.file-upload');
    }
}
