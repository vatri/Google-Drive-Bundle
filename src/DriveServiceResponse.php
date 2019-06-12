<?php
/**
 * Created by PhpStorm.
 * User: trivicb
 * Date: 6/12/2019
 * Time: 12:56 PM
 */

namespace Vatri\GoogleDriveBundle;


class DriveServiceResponse
{
    /**
     * @var string
     */
    private $resource_id;

    /**
     * @var string
     */
    private $error;

    /**
     * DriveServiceResponse constructor.
     * @param string $resource_id
     * @param string $error
     */
    public function __construct(string $resource_id = '', string $error = '')
    {
        $this->resource_id = $resource_id;
        $this->error = $error;
    }


    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->resource_id;
    }

    /**
     * @param string $resource_id
     */
    public function setResourceId($resource_id)
    {
        $this->resource_id = $resource_id;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }


}