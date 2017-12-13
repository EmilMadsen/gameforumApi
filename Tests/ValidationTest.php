<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 07-12-2017
 * Time: 09:56
 */

require ($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Logic/Validation.php');

class ValidationTest extends PHPUnit_Framework_TestCase
{
    public function test_valid_usernames_and_passwords_on_boundaries_values()
    {   // Arrange
        $usernames = array("Test", "VeryVeryVeryVeryLongUsername1234", "Regular1");
        $passwords = array("VeryVeryVeryVeryLongPassword1234", "Short1", "CoolPassword2");
        $results = array();
        // Act
        for ($i = 0; $i <= 2; $i++) {
            array_push($results, Validation::isValidUsername($usernames[$i]));
            array_push($results, Validation::isValidPassword($passwords[$i]));
        }
        // Assert
        foreach ($results as $result) {
            assert(true, $result);
        }
    }

    public function test_invalid_username_contains_nonAlphanumeric_character()
    {   // Arrange - Static method so no need
        $results = array();
        // Act
        array_push($results,Validation::isValidUsername("-Regular1"));
        array_push($results,Validation::isValidUsername(".Regular1"));
        array_push($results,Validation::isValidUsername("/Regular1"));
        //Assert
        foreach ($results as $result) {
            assert(false, $result);
        }
    }

    public function test_invalid_username_to_short()
    {
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidUsername("Hej");

        //Assert
        assert(false, $result);
    }

    public function test_invalid_username_to_long(){
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidUsername("VeryVeryVeryVeryLongUsername12345");

        //Assert
        assert(false, $result);
    }

    public function test_invalid_password_to_short(){
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidPassword("Pass1");

        //Assert
        assert(false,$result);
    }

    public function test_invalid_password_to_long(){
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidPassword("VeryVeryVeryVeryLongPassword12345");

        //Assert
        assert(false,$result);
    }

    public function test_invalid_password_contains_no_uppercase(){
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidPassword("coolpassword2");

        //Assert
        assert(false,$result);
    }

    public function test_invalid_password_contains_no_lowercase(){
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidPassword("COOLPASSWORD2");

        //Assert
        assert(false,$result);
    }

    public function test_invalid_password_contains_no_number(){
        // Arrange - Static method so no need
        // Act
        $result = Validation::isValidPassword("CoolPassword");

        //Assert
        assert(false,$result);
    }
}


