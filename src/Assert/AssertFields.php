<?php

namespace NovaTesting\Assert;

use closure;
use Illuminate\Support\Collection;
use Illuminate\Testing\Assert as PHPUnit;

trait AssertFields
{
    public function assertFieldCount($amount)
    {
        $path = isset($this->original['resources']) ? 'resources.0.fields' : $this->getFieldsPath();

        $this->assertJsonCount($amount, $path);

        return $this;
    }

    public function assertFields(closure $callable)
    {
        if (isset($this->original['resources'][0]['fields'])) {
            $path = 'resources.*.fields';
        } elseif (isset($this->original['resource']['fields'])) {
            $path = 'resource.fields';
        } elseif (isset($this->original['fields'])) {
            $path = 'fields';
        }

        $fields = collect(json_decode(json_encode(data_get($this->original, $path, []), true)));

        $isCallingOk = $callable($fields) ? true : false;

        PHPUnit::assertTrue($isCallingOk);

        return $this;
    }

    public function assertFieldsInclude($attribute, $value=null)
    {
        return $this->fieldCheck($attribute, $value, 'assertJsonFragment');
    }

    public function assertFieldsExclude($attribute, $value=null)
    {
        return $this->fieldCheck($attribute, $value, 'assertJsonMissing');
    }

    protected function fieldCheck($attribute, $value = null, $method)
    {
        if ($attribute instanceof Collection) {
            $attribute = $attribute->toArray();
        }

        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        // ->assertFieldsInclude('id')
        if (!is_array($attribute) && is_null($value)) {
            return $this->assertFieldAttribute($attribute, $method);
        }

        // ->assertFieldsInclude('id', [1,2,3])
        if (!is_array($attribute) && is_array($value)) {
            return $this->assertFieldManyValues($attribute, $value, $method);
        }

        // ->assertFieldsInclude('id', 1)
        if (!is_array($attribute) && !is_null($value)) {
            return $this->assertFieldValue($attribute, $value, $method);
        }

        // ->assertFieldsInclude(['id', 'email'])
        if (is_array($attribute) && is_numeric(array_keys($attribute)[0])) {
            return $this->assertFieldKeys($attribute, $method);
        }

        // ->assertFieldsInclude(['id' => 1])
        if (is_array($attribute)) {
            return $this->assertFieldKeyValue($attribute, $method);
        }

        return $this;
    }

    public function assertFieldAttribute($attribute, $method)
    {
        return $this->$method([
            'attribute' => $attribute
        ]);
    }
    public function assertFieldOptions($attribute, $options,$method)
    {
        $formattedOptions = collect($options)->map(function ($value, $label) {
            return [
                'label' => $label,
                'value' => $value,
            ];
        })->values()->all();

        return $this->$method([
            'attribute' => $attribute,
            'options' => $formattedOptions
        ]);
    }

    public function assertFieldRequired($attribute, $required, $method)
    {
        return $this->$method([
            'attribute' => $attribute,
            'required' => $required
        ]);
    }

    public function assertFieldValue($attribute, $value, $method)
    {
        return $this->$method([
            'attribute' => $attribute,
            'value' => $value,
        ]);
    }

    public function assertFieldManyValues($attribute, array $value, $method)
    {
        foreach ($value as $v) {
            $this->assertFieldValue($attribute, $v, $method);
        }

        return $this;
    }

    public function assertFieldKeys(array $keys, $method)
    {
        foreach ($keys as $key) {
            $this->assertFieldAttribute($key, $method);
        }

        return $this;
    }

    public function assertFieldKeyValue($attribute, $method)
    {
        foreach ($attribute as $attr => $value) {
            $this->assertFieldValue($attr, $value, $method);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getFieldsPath()
    {
        if ( isset($this->original[ 'resources' ][ 0 ][ 'fields' ]) ) {
            return 'resources.*.fields';
        } elseif ( isset($this->original[ 'resource' ][ 'fields' ]) ) {
            return 'resource.fields';
        }

        return 'fields';
    }
}
