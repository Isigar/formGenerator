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


/**
 * Class TypeSelector
 * - Třída zjištuje typ z databáze (varchar, int, float, datetime) a převádí ho do INPUT typu.
 * @package App\BackendModule\Model\FormGenerator
 */
class TypeSelector
{
    /**
     * STRUCTURE
     * - name
     * - nativetype (varchar, int, float)
     * - vendor
     * -- comment (dontshow,current)
     */

    /*TYPES*/
    const TYPE_STRING = 'VARCHAR';
    const TYPE_NUMBER = 'INT';
    const TYPE_DATE = 'DATETIME';

    /*BETTER STRUCTURE NAMES*/
    const ST_TYPE = 'nativetype';
    const ST_NAME = 'name';
    const ST_SIZE = 'size';
    const ST_COMMENT = 'Comment';
    const ST_EXTENDS = 'vendor';

    /*TYPE OF INPUT*/
    const INPUT_DATETIME = 'datetime-local';
    const INPUT_DATETIME_CURRENT = 'datetime-local current';
    const INPUT_DEFAULT = 'addText';
    const INPUT_INT = 'number';
    const INPUT_EMAIL = 'email';

    const TEXTAREA = 'addTextArea';
    const UPLOAD = 'addUpload';
    const MULTI_UPLOAD = 'addMultiUpload';

    /*TYPE OF COMMENT API*/
    const API_HIDE = 'dontshow';
    const API_EMAIL = 'email';
    const API_UPLOAD = 'upload';
    const API_CURRENT_DATE = 'current';

    /*RETURN TYPES
     array(
        "func" => "addText"
        "type" => "number"
        "defaultValue" => ""
    )
    */

    /*TYPE OF LIMITS*/
    /**
     * If input has lenght more than 200 is recognize as textarea!
    */
    const LIMIT_INPUT = '200';


    /**
     * Vrací typ s využitím API
     *
     * @param array $structure
     *
     * @return string|void
     */
    public static function match(array $structure)
    {
        if(self::canShow($structure))
        {
            switch ($structure[self::ST_TYPE])
            {
                case self::TYPE_STRING:
                    if($structure[self::ST_SIZE] > self::LIMIT_INPUT)
                    {
                        return array(
                            "func" => self::TEXTAREA,
                            "name" => $structure[self::ST_NAME],
                            "type" => "",
                            "defaultValue" => "",
                        );
                    }
                    elseif(self::getComment($structure) == self::API_EMAIL)
                    {
                        return array(
                            "func" => self::INPUT_DEFAULT,
                            "name" => $structure[self::ST_NAME],
                            "type" => "email",
                            "defaultValue" => "",
                        );
                    }
                    elseif(self::getComment($structure) == self::API_UPLOAD)
                    {
                        return array(
                            "func" => self::UPLOAD,
                            "name" => $structure[self::ST_NAME],
                            "type" => "",
                            "defaultValue" => "",
                        );
                    }
                    else
                    {
                        return array(
                            "func" => self::INPUT_DEFAULT,
                            "name" => $structure[self::ST_NAME],
                            "type" => "",
                            "defaultValue" => "",
                        );
                    }
                    break;
                case self::TYPE_DATE:
                    if(self::getComment($structure) == self::API_CURRENT_DATE)
                    {
                        return array(
                            "func" => self::INPUT_DEFAULT,
                            "name" => $structure[self::ST_NAME],
                            "type" => "",
                            "defaultValue" => (new DateTime()),
                        );
                    }
                    else
                    {
                        return array(
                            "func" => self::INPUT_DEFAULT,
                            "name" => $structure[self::ST_NAME],
                            "type" => self::INPUT_DATETIME,
                            "defaultValue" => "",
                        );
                    }
                    break;
                case self::TYPE_NUMBER:
                    return array(
                        "func" => self::INPUT_DEFAULT,
                        "name" => $structure[self::ST_NAME],
                        "type" => self::INPUT_INT,
                        "defaultValue" => "",
                    );
                    break;
            }
        }
        else
        {
            return;
        }
    }

    private static function canShow(array $structure)
    {
        if($structure[self::ST_EXTENDS][self::ST_COMMENT] == self::API_HIDE)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private static function getComment(array $structure)
    {
        return $structure[self::ST_EXTENDS][self::ST_COMMENT];
    }
}