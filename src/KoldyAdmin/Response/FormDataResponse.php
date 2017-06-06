<?php declare(strict_types=1);

namespace KoldyAdmin\Response;

use Koldy\Util;
use KoldyAdmin\Exception;

class FormDataResponse extends Json
{
    private $formData = [];

    /**
     * @param string $key
     * @param $value
     *
     * @return FormDataResponse
     */
    public  function setFormDataKey(string $key, $value): self
    {
        $this->formData[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasFormDataKey(string $key): bool
    {
        return array_key_exists($key, $this->formData);
    }

    /**
     * @param array $data
     *
     * @return FormDataResponse
     */
    public function setFormData(array $data): self
    {
        $this->formData = array_merge($this->formData, $data);
        return $this;
    }

    protected function prepareFlush(): void
    {
        parent::prepareFlush();
        $this->set('data', $this->formData);
    }

    /**
     * @param string $dataKey
     * @param $value
     *
     * @return FormDataResponse
     */
    public function setTextFieldValue(string $dataKey, $value): self
    {
        $this->setFormDataKey($dataKey, $value);
        return $this;
    }

    /**
     * @param string $dataKey
     * @param array $options
     *
     * @return FormDataResponse
     * @throws Exception
     */
    public function setSelectOptions(string $dataKey, array $options): self
    {
        $elements = [];

        if (Util::isAssociativeArray($options)) {
            foreach ($options as $value => $label) {
                $elements[] = [
                    'value' => $value,
                    'label' => $label
                ];
            }
        } else {
            // it's indexed array
            foreach ($options as $option) {
                if (is_array($option)) {
                    if (!Util::isAssociativeArray($option) && count($option) == 2) {
                        $elements[] = [
                          'value' => $option[0],
                          'label' => $option[1]
                        ];
                    } else if (Util::isAssociativeArray($option) && count($option) == 1) {
                        $elements[] = [
                          'value' => array_keys($option)[0],
                          'label' => array_values($option)[0]
                        ];
                    } else if (count($option) == 2 && array_key_exists('label', $option) && array_key_exists('value', $option)) {
                        $elements[] = [
                          'value' => $option['value'],
                          'label' => $option['label']
                        ];
                    } else {
                        throw new Exception("Unable to generate select options for dataKey={$dataKey}, got unsupported data type for option element");
                    }

                } else if (is_string($option) || is_numeric($option)) {
                    $elements[] = [
                      'value' => $option,
                      'label' => $option
                    ];
                } else {
                    throw new Exception("Unable to generate select options for dataKey={$dataKey}, got unsupported data type: " . gettype($option));
                }
            }
        }

        $this->setFormDataKey($dataKey, $elements);
        return $this;
    }

    /**
     * @param string $dataKey
     * @param array $options
     *
     * @return FormDataResponse
     * @throws Exception
     */
    public function setRadioOptions(string $dataKey, array $options): self
    {
        $elements = [];

        if (Util::isAssociativeArray($options)) {
            foreach ($options as $value => $label) {
                $elements[] = [
                    'value' => $value,
                    'label' => $label
                ];
            }
        } else {
            // it's indexed array
            foreach ($options as $option) {
                if (is_array($option)) {
                    if (!Util::isAssociativeArray($option) && count($option) == 2) {
                        $elements[] = [
                          'value' => $option[0],
                          'label' => $option[1]
                        ];
                    } else if (Util::isAssociativeArray($option) && count($option) == 1) {
                        $elements[] = [
                          'value' => array_keys($option)[0],
                          'label' => array_values($option)[0]
                        ];
                    } else if (count($option) == 2 && array_key_exists('label', $option) && array_key_exists('value', $option)) {
                        $elements[] = [
                          'value' => $option['value'],
                          'label' => $option['label']
                        ];
                    } else {
                        throw new Exception("Unable to generate select options for dataKey={$dataKey}, got unsupported data type for option element");
                    }

                } else if (is_string($option) || is_numeric($option)) {
                    $elements[] = [
                      'value' => $option,
                      'label' => $option
                    ];
                } else {
                    throw new Exception("Unable to generate select options for dataKey={$dataKey}, got unsupported data type: " . gettype($option));
                }
            }
        }

        $this->setFormDataKey($dataKey, $elements);
        return $this;
    }
}