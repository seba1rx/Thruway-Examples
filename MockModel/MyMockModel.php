<?php

namespace MockModel;

class MyMockModel
{
    private static $myMockUsersDB = [
        1 => ["user" => "mark", "password" => "12345"],
        2 => ["user" => "john", "password" => "23456"],
        3 => ["user" => "anna", "password" => "34567"],
        4 => ["user" => "lisa", "password" => "45678"],
    ];

    public static function getMyMockData(): array
    {
        sleep(5); // it takes time to get mock data
        return [
            "foo" => "Lorem ipsum dolor sit amet, consectetur.",
            "bar" => "Adipiscing elit. Quisque vel placerat neque.",
        ];
    }

    /**
     * checks if the user and password are ok
     *
     * @param string $user
     * @param string $password
     * @return int|false
     */
    public static function checkCredentials($user, $password): int|false
    {
        foreach(self::$myMockUsersDB as $user_id => $row){
            if($row["user"] == $user && $row["password"] == $password){
                return $user_id;
            }
        }

        return false;
    }

}
