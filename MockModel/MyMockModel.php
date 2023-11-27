<?php

namespace MockModel;

class MyMockModel
{
    private static $myMockUsersDB = [
        ["user_id" => 1, "user" => "mark", "password" => "12345"],
        ["user_id" => 2, "user" => "john", "password" => "23456"],
        ["user_id" => 3, "user" => "anna", "password" => "34567"],
        ["user_id" => 4, "user" => "lisa", "password" => "45678"],
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
        foreach(self::$myMockUsersDB as $row){
            if($row["user"] == $user && $row["password"] == $password){
                return $row["user_id"];
            }
        }

        return false;
    }

    public static function showUsers(): array
    {
        return self::$myMockUsersDB;
    }

    public static function getUserById(int $user_id): array|false
    {
        foreach(self::$myMockUsersDB as $row){
            if($row['user_id'] == $user_id){
                return $row;
            }
        }
        return false;
    }

}
