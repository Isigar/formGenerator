<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 20.11.2016
 * Time: 14:54
 */

namespace App\BackendModule\Model\FormGenerator;


use Nette\Utils\DateTime;
use Tracy\Dumper;

class FormGenerator
{
    public function __construct()
    {

    }

    /**
     * @param      $form
     * @param      $structure
     *
     * @return mixed
     */
    public function generate($form, $structure)
    {
        foreach($structure as $one)
        {
            $pattern = TypeSelector::match($one);

            if($pattern)
            {
                if($pattern["defaultValue"] instanceof DateTime)
                {
                    $func = $pattern["func"];
                    $form->$func($pattern["name"],$this->readable($pattern["name"]))
                        ->setType($pattern["type"])
                        ->setDisabled()
                        ->setValue($pattern["defaultValue"]);
                }
                elseif($pattern["func"] == TypeSelector::UPLOAD)
                {
                    $func = $pattern["func"];
                    $form->$func($pattern["name"],$this->readable($pattern["name"]));
                }
                else
                {
                    $func = $pattern["func"];
                    $form->$func($pattern["name"],$this->readable($pattern["name"]))
                        ->setType($pattern["type"])
                        ->setDefaultValue($pattern["defaultValue"]);
                }

            }
            else
            {
                continue;
            }
        }

        return $form;
    }

    private function readable($label)
    {
        $label = @str_replace("_"," ",$label);
        return ucfirst($label);
    }
}