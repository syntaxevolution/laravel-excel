<?php namespace Macellan\Excel\Files;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Macellan\Excel\Excel;
use Macellan\Excel\Exceptions\LaravelExcelException;
use Macellan\Excel\Readers\LaravelExcelReader;

abstract class File {

    /**
     * @var Application
     */
    protected $app;

    /**
     * Excel instance
     * @var Excel
     */
    protected $excel;

    /**
     * Loaded file
     * @var LaravelExcelReader
     */
    protected $file;

    /**
     * @param Application $app
     * @param Excel       $excel
     */
    public function __construct(Application $app, Excel $excel)
    {
        $this->app = $app;
        $this->excel = $excel;
    }

    /**
     * Handle the import/export of the file
     * @param $type
     * @throws LaravelExcelException
     * @return mixed
     */
    public function handle($type)
    {
        // Get the handler
        $handler = $this->getHandler($type);

        // Call the handle method and inject the file
        return $handler->handle($this);
    }

    /**
     * Get handler
     * @param $type
     * @return mixed
     * @throws BindingResolutionException
     * @throws LaravelExcelException
     */
    protected function getHandler($type)
    {
        return $this->app->make(
            $this->getHandlerClassName($type)
        );
    }

    /**
     * Get the file instance
     * @return mixed
     */
    public function getFileInstance()
    {
        return $this->file;
    }

    /**
     * Get the handler class name
     * @param $type
     * @return string
     * @throws LaravelExcelException
     */
    protected function getHandlerClassName($type)
    {
        // Translate the file into a FileHandler
        $class = get_class($this);
        $handler = substr_replace($class, $type . 'Handler', strrpos($class, $type));

        // Check if the handler exists
        if (!class_exists($handler))
            throw new LaravelExcelException("$type handler [$handler] does not exist.");

        return $handler;
    }
}