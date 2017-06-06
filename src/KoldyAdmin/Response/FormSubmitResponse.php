<?php declare(strict_types=1);

namespace KoldyAdmin\Response;

use KoldyAdmin\Exception;

class FormSubmitResponse extends Json
{

    /**
     * @param string $key
     * @param $value
     *
     * @return $this
     */
    private function setResponseData(string $key, $value)
    {
        $form = $this->get('form');

        if (!is_array($form)) {
            $form = [];
        }

        $form[$key] = $value;

        $this->set('form', $form);
        return $this;
    }

    /**
     * @param string $content
     * @param string $color
     *
     * @return FormSubmitResponse
     * @throws Exception
     */
    public function setFormMessage(string $content, string $color = 'danger'): self
    {
        $this->checkState($color);

        $this->setResponseData('message', [
          'content' => $content,
          'state' => $color
        ]);

        return $this;
    }

    /**
     * @param string $name
     * @param string $content
     * @param string $state
     *
     * @return FormSubmitResponse
     */
    public function setFieldMessage(string $name, string $content, string $state = 'danger'): self
    {
        $this->checkState($state);

        $form = $this->get('form');

        if (!is_array($form)) {
            $form = [];
        }

        $fields = $form['fields'] ?? null;

        if (!is_array($fields)) {
            $fields = [];
        }

        $fields[$name] = [
          'name' => $name,
          'content' => $content,
          'state' => $state
        ];

        $this->setResponseData('fields', $fields);

        return $this;
    }

    /**
     * @param array $fieldErrors
     *
     * @return FormSubmitResponse
     */
    public function setFieldErrors(array $fieldErrors): self
    {
        foreach ($fieldErrors as $field => $error) {
            $this->setFieldMessage($field, $error);
        }
        return $this;
    }
}