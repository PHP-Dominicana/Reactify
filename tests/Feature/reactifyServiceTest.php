<?php

use PHPDominicana\Reactify\Enums\Reaction;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

it('adds a reaction to a reactable model', function () {
    $user = User::factory()->create();;
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);

    expect($post->reactions(Reaction::LIKE))->toBe(1);
});

it('remove a reaction to a reactable model', function () {
    $user = User::factory()->create();;
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(1);

    $post->unReact($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(0);
});
