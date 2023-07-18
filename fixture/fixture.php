<?php

function foo(int $value, int $value2): string
{
    if ($value === 5) {
        if ($value2 === 10) {
            return 'yes';
        }
    }

    return 'no';
}


function bar(int $value)
{
    if ($value) {
        throw new \InvalidArgumentException;
    } else {
        return 10;
    }
}