<?php

namespace MockModel;

class MyMockModel
{
    public static function getMyMockData(): array
    {
        sleep(5); // it takes time to get mock data
        return [
            "foo" => "Lorem ipsum dolor sit amet, consectetur.",
            "bar" => "Adipiscing elit. Quisque vel placerat neque.",
        ];
    }
}
