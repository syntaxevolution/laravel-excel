<?php namespace Macellan\Excel\Files;

use Illuminate\Contracts\Foundation\Application;
use Macellan\Excel\Excel;
use Macellan\Excel\Exceptions\LaravelExcelException;
use Macellan\Excel\Writers\LaravelExcelWriter;

abstract class NewExcelFile extends File {

    /**
     * @param Application $app
     * @param Excel       $excel
     */
    public function __construct(Application $app, Excel $excel)
    {
        parent::__construct($app, $excel);
        $this->file = $this->createNewFile();
    }

    /**
     * Get file
     * @return string
     */
    abstract public function getFilename();

    /**
     * Start importing
     * @throws LaravelExcelException
     */
    public function handleExport()
    {
        return $this->handle( 
            get_class($this) 
        );
    }


    /**
     * Load the file
     * @return LaravelExcelWriter
     */
    public function createNewFile()
    {
        // Load the file
        return $this->excel->create(
            $this->getFilename()
        );
    }

    /**
     * Dynamically call methods
     * @param  string $method
     * @param  array  $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->file, $method], $params);
    }

}