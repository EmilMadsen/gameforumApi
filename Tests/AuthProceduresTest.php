<?php
/**
 * Created by PhpStorm.
 * User: Kaempe
 * Date: 07-12-2017
 * Time: 23:53
 */

require ($_SERVER["DOCUMENT_ROOT"].'/gameforumApi/Repositories/AuthProcedures.php');

class AuthProceduresTest extends PHPUnit_Framework_TestCase
{

    public function test_create_valid_users_on_boundaries_values(){
    // Arrange
    $procedures = new AuthProcedures();
    $usernames = array("Test","VeryVeryVeryVeryLongUsername1234", "Regular1");
    $passwords = array("VeryVeryVeryVeryLongPassword1234","Short1","CoolPassword2");
    // Act
    for ($i = 0; $i <= 2; $i++) {
        $procedures->createUser($usernames[$i],$passwords[$i],uniqid());
    }
    // Assert - No Errors should occur
}

    /**
     * @depends test_create_valid_users_on_boundaries_values
     * @expectedException PDOException
     */
    public function test_create_invalid_user_username_already_exists(){
        // Arrange
        $procedures = new AuthProcedures();
        // Act
        $procedures->createUser("Regular1","CoolPassword2",uniqid());
        //Assert - Expecting a PDOException
    }

    /**
     * @expectedException PDOException
     */
    public function test_create_invalid_user_username_to_short(){
        // Arrange
        $procedures = new AuthProcedures();
        // Act
        $procedures->createUser("Hej","CoolPassword2",uniqid());
        //Assert - Expecting a PDOException
    }

    /**
     * @expectedException PDOException
     */
    public function test_create_invalid_user_username_to_long(){
        // Arrange
        $procedures = new AuthProcedures();
        // Act
        $procedures->createUser("VeryVeryVeryVeryLongUsername12345","CoolPassword2",uniqid());
        //Assert - Expecting a PDOException
    }

    /**
     * @expectedException PDOException
     */
    public function test_create_invalid_user_password_to_long(){
        // Arrange
        $procedures = new AuthProcedures();
        // Act
        $procedures->createUser("Regular1","VeryVeryVeryVeryLongPassword12345",uniqid());
        //Assert - Expecting a PDOException
    }

}
