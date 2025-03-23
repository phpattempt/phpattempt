<?php

test('attempt() returns 2 for 1 + 1 and empty error', function () {
    [$data, $error] = attempt(fn () => 1 + 1);

    expect($data)->toBe(2);
    expect($error)->toBeNull();
});

test('attempt() with callable string returns empty data and runtime exception', function () {
    function sumOnePlusOneWithError() {
        throw new RuntimeException('Something went wrong');

        return 1 + 1;
    }

    [$data, $error] = attempt('sumOnePlusOneWithError');

    expect($data)->toBeNull();
    expect($error)->toBeInstanceOf(RuntimeException::class);
    expect($error->getMessage())->toBe('Something went wrong');
});

test('attempt() with arrow fn returns empty data and runtime exception', function () {
    [$data, $error] = attempt(fn () => throw new RuntimeException('Something went wrong'));

    expect($data)->toBeNull();
    expect($error)->toBeInstanceOf(RuntimeException::class);
    expect($error->getMessage())->toBe('Something went wrong');
});

test('attempt() with callback function returns empty data and runtime exception', function () {
    [$data, $error] = attempt( function () {
        throw new RuntimeException('Something went wrong');

        return 1 + 1;
    });

    expect($data)->toBeNull();
    expect($error)->toBeInstanceOf(RuntimeException::class);
    expect($error->getMessage())->toBe('Something went wrong');
});

test('attempt() inside attempt()', function () {
    [$data, $error] = attempt(function () {
        [$data, $error] = attempt(function () {
            throw new RuntimeException();
        });

        if (null !== $data) {
            throw new RuntimeException('This shouldn\'t have happened!');
        }

        if (null === $error) {
            throw new RuntimeException('This shouldn\'t have happened!');
        }

        return true;
    });

    expect($data)->toBeTrue();
    expect($error)->toBeNull();
});