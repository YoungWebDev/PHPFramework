<?php


namespace app\Model;


class Validator {

    public $passed = false;

    public $errors = [];

    public $form = [];

    public function validate($form)
    {

        foreach($form as $name => $value)
        {
            if ( $value == "" && $name != "nfz" )
            {
                array_push($this->errors, $name);
            }

            if ($name == "phone")
            {
               if (!preg_match("/^[0-9]*$/", $value) || strlen($value) !== 9)
               {
                   array_push($this->errors, $name);
               }
            }

            if ($name == "email")
            {
                if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value) || strlen($value) > 60)
                {
                    array_push($this->errors, $name);
                }
            }

            if ($name == "name" || $name == "surrname")
            {
                if (!preg_match("/^[a-ząćęłńóśźżA-ZĄĆĘŁŃÓŚŻŹ]*$/", $value) || strlen($value) > 30)
                {
                    array_push($this->errors, $name);
                }
            }

            if ($name == "date")
            {
                if (!preg_match("/^[0-9-]*$/", $value) || strlen($value) !== 10)
                {
                    array_push($this->errors, $name);
                }
            }

            if ($name == "doc" && $name == "cc" && $name == "shop")
            {
                if (!preg_match("/^[0-9]*$/", $value) || strlen($value) > 3)
                {
                    array_push($this->errors, $name);
                }
            }

            if ($name == "nfz")
            {
                if (!preg_match("/^[NFZPRYWATN]*$/", $value) || strlen($value) > 8)
                {
                    array_push($this->errors, $name);
                }
            }

        }

        if (empty($this->errors)) {
            $this->passed = true;
        } else {
            $this->form = $form;
        }

    }



}