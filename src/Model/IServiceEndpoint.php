<?php
/**
 * Created by PhpStorm.
 * User: PBX_g33k
 * Date: 01/04/2018
 * Time: 23:20
 */

namespace Pbxg33k\InfoBase\Model;


use Doctrine\Common\Collections\ArrayCollection;

interface IServiceEndpoint
{
    /**
     * @param $apiService
     *
     * @return mixed
     */
    public function setParent($apiService);

    /**
     * Transform single item to model
     *
     * @param $raw
     *
     * @return BaseModel
     */
    public function transformSingle($raw);

    /**
     * Transform collection to models
     *
     * @param $raw
     *
     * @return ArrayCollection
     */
    public function transformCollection($raw);

    /**
     * Transform to models
     *
     * @param $raw
     *
     * @return ArrayCollection
     */
    public function transform($raw);

    /**
     * @return mixed
     */
    public function getParent();

    /**
     * @param $arguments
     *
     * @return mixed
     */
    public function get($arguments);

    /**
     * @param $arguments
     *
     * @return mixed
     */
    public function getComplete($arguments);

    /**
     * The default entrypoint for searching entities.
     *
     * @param $arguments
     * @return mixed
     */
    public function search($arguments);
}