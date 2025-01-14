<?php

namespace ethercap\apiBase\widgets;

use yii\base\InvalidConfigException;
use ethercap\apiBase\components\Serializer;

class ModelsApi extends ListApi
{
    /**
     * @ Model[] | callable with function($model)
     */
    public $models;

    public $serializerOptions = [
    ];

    /**
     * @var Serializer
     */
    private $_serializer;

    /**
     * Initializes the Api.
     */
    public function init()
    {
        if ($this->models === null) {
            throw new InvalidConfigException('The "models" property must be set.');
        }
        $this->_serializer = new $this->serializer($this->serializerOptions
            + ['columns' => $this->columns] + ['useModelResponse' => $this->useModelResponse]);
    }

    public function run()
    {
        if (is_callable($this->models)) {
            $this->models = call_user_func($this->models, $this->context);
        }

        $rtData = $this->_serializer->serializeModels($this->models);
        if ($errModel = $this->_serializer->errInstance) {
            $this->builder->pushError($errModel);
        }
        return $rtData;
    }
}
