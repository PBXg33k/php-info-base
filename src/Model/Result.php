<?php
/**
 * Created by PhpStorm.
 * User: PBX_g33k
 * Date: 29/04/2018
 * Time: 14:53
 */

namespace Pbxg33k\InfoBase\Model;


class Result extends BaseModel
{
    /**
     * @var boolean
     */
    protected $error;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * @param bool $error
     * @return Result
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Result
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }


}