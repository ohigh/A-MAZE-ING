<?php

    
    
    //////////////////////////////////////////////
    ////  Validering af navn (Kun bogstaver) ///// 
    //////////////////////////////////////////////
    // $data = formular input ($_POST['INPUT NAVN'])
    // $min = mindste antal bogstaver - default=2
    // $max = maximale antal bogstaver  - default=30

    /**
     * Validering af navn (Kun bogstaver)
     * @param STRING $data
     * @param INT $min
     * @param INT $max
     * @return Boolean
     */
    

    function validCharacter($data, $min=2, $max=30) {
        return (
            isset($data) && 
            strlen($data) >= $min &&
            strlen($data) <= $max &&
            preg_match("/[a-zæøåüöä]+$/i", $data)
        )
            // shorthand if / else
            ? true
            : false;
    }
    // Test af validering
    // echo validName('Ole') ? 'succsess' : 'error';




    //////////////////////////////////////////////
    ////  Validering af dato (Med / eller -) ///// 
    //////////////////////////////////////////////
    // $data = formular input ($_POST['INPUT NAVN'])
    

    /**
     * Validering af dato uden tid
     * @param STRING $data
     * @return Boolean
     */

    function validDate($data) {
        return (
            isset($data) && 
           (preg_match("~^\d{2}/\d{2}/\d{4}$~", $data) ||
            preg_match("~^\d{2}-\d{2}-\d{4}$~", $data) ||
            preg_match("~^\d{4}/\d{2}/\d{2}$~", $data) ||
            preg_match("~^\d{4}-\d{2}-\d{2}$~", $data))
        )   ? true
            : false;    
    }



    
    ////////////////////////////////////////////////////
    ////  Validering af eks. adresse (Bogstaver og tal) ///// 
    ////////////////////////////////////////////////////
    // $data = formular input ($_POST['INPUT NAVN'])
    // $min = mindste antal bogstaver - default=2
    // $max = maximale antal bogstaver  - default=30

    /**
     * Validering af adresse (Bogstaver og tal)
     * @param STRING $data
     * @param INT $min
     * @param INT $max
     * @return Boolean
     */

    function validStringBetween($data, $min, $max) {
        return (
            isset($data) && 
            preg_match("/[a-zæøåüöä 0-9,.]+$/i", $data) &&
            (strlen($data) >= $min) &&
            (strlen($data) <= $max)
        )   ? true
            : false;  
    }

    ////////////////////////////////////////////////////
    ////  Validering af STRING (Alt er tilladt) ///// 
    ////////////////////////////////////////////////////
    // $data = formular input ($_POST['INPUT NAVN'])
    // $min = mindste antal bogstaver - default=Ingen
    // $max = maximale antal bogstaver  - default=255

    /**
     * Validering af adresse (Bogstaver og tal)
     * @param STRING $data
     * @param INT $min
     * @param INT $max
     * @return Boolean
     */

    function validMixedBetween($data, $min, $max = 255) {
        return (
            isset($data) && 
            (strlen($data) >= $min) &&
            (strlen($data) <= $max)
        )   ? true
            : false;  
    }




    //////////////////////////////////////
    ////  Validering af postnr (tal) ///// 
    //////////////////////////////////////
    // $data = formular input ($_POST['INPUT NAVN'])
    // $min = mindste antal bogstaver - default=2
    // $max = maximale antal bogstaver  - default=30

    /**
     * Validering af postnr (tal)
     * @param INT $data
     * @param INT $min
     * @param INT $max
     * @return Boolean
     */    

    function validIntBetween ($data, $min, $max) {
        return (
            isset($data) && 
            is_numeric($data) &&
            (strlen($data) >= $min) &&
            (strlen($data) <= $max)
        )   ? true
            : false;  
    }

    /////////////////////////////////////////////
    ////  Validering af telefonnummer (tal) ///// 
    /////////////////////////////////////////////
    // $number = formular input ($_POST['INPUT NAVN'])
    // Fjern mellemrum
    // Fjern evt. +45 eller 0045
    
    
    // /**
    //  * Validering af telefonnummer (tal)
    //  * @param STRING $number
    //  * @return INT (8)
    //  */    
    function validPhone($input) {
        if(strlen($input) > 8) {
            $input = str_replace(' ', '', $input);
            $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
            $input = preg_replace('/0045/', '', $input);
           
        }   if(is_numeric($input) && strlen($input) == 8) {
            return $input;
            } return false;
    }
    

    /////////////////////////////////////////
    ////  Validering af email (varchar) ///// 
    /////////////////////////////////////////
    // $mail = formular input ($_POST['INPUT NAVN'])
    

    /**
     * Validering af email (varchar)
     * @param STRING $mail
     * @return Boolean
     */    

    function validEmail($mail) {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validering om datatyper (varchar)
     * @param MIXED $x
     * @param MIXED $y
     * @return Boolean
     */    

    function validMatch($x, $y) {
        return ($x === $y) ? true : false;
    }