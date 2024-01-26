<?php

declare(strict_types=1);

namespace DEMO6;

class MockMsg{

    private static $greetings = [
        "Hello",
        "How do you do",
        "it's been a while",
        "how're you doing",
        "Nice to meet you",
        "how's it going?",
        "what's up",
        "Hi",
        "Good morning",
        "Good to see you again",
        "hahahaha",
        "What time is it?",
        "I'm tired!!!",
        "what about Joe?",
        "is anyone interested in gardening?",
        "Nahhh",
        "I'm off",
        "I'm hungry, who is up for McDonalds??",
        "lets go get some McDonalds!!!!",
        "I've got a lot to do",
        "hey, you again?",
        "I thought it was just me!",
        "hooooorayyy!!",
        "I need to go feed my pets, bye",
        "how many cats do you have",
        "I don't have any cats, just dogs",
        "but how many then",
        "four, all small",
        "wow, thats a lot",
        "it gets worse and worse by the minute",
        "are you doing something this weekend?",
        "I want to go to the movies",
        "I rather just stay at home",
        "well, that escalated quickly",
        "hola, mucho gusto",
        "do you speak spanish?",
        "na, just know enough to say hello hahahah",
        "Hey I saw you the last night, you were buyng a burrito!",
        "mmmm, last time I went out for a burrito was 2 weeks ago, so I think it wasn't me, sorry",
        "oh",
        "dammit, now I'm thinking about getting a burrito!",
        "ok, lets go to that burrito joint near the train station, see you there in 15",
        "ahhh damn, no, sorry, it will have to be in 40, I need to get the washing machine going and then I can go out",
    ];

    public static function getRandomMockGreeting(): string
    {
        $key = array_rand(self::$greetings);
        return self::$greetings[$key];
    }

    public static function getGreetingsArray(): array
    {
        return self::$greetings;
    }
}