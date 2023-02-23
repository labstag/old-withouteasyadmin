<?php

namespace Labstag\Service;

class FormService
{
    public function __construct(protected $formfront, protected $postform)
    {
    }

    public function all()
    {
        return $this->formfront;
    }

    public function get($name)
    {
        $form = null;
        foreach ($this->formfront as $form) {
            if ($form->getName() == $name) {
                break;
            }
        }

        return $form;
    }

    public function test()
    {
        $success = [];
        foreach ($this->formfront as $form) {
            $success = $this->execute($form, $success, $form->getName());
        }

        return $success;
    }

    private function execute(
        $form,
        $success,
        $formName
    )
    {
        $formClass = $form::class;
        foreach ($this->postform as $row) {
            if ($row->getForm() == $formClass) {
                $success = $row->execute($formClass, $success, $formName);

                break;
            }
        }

        return $success;
    }
}
