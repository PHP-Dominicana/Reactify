<?php

use PHPDominicana\Reactify\Enums\Reaction;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

it('adds a reaction to a reactable model', function (): void {
    $user = User::factory()->create();;
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);

    expect($post->reactions(Reaction::LIKE))->toBe(1);
});

it('remove a reaction to a reactable model', function (): void {
    $user = User::factory()->create();;
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(1);

    $post->unReact($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(0);
});

it('add manny  diff reaction to model', function (): void {
    [$user1, $user2] = User::factory(2)->create();;
    $post = Post::factory()->create();

    $post->react($user1->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(1);

    $post->react($user2->id, Reaction::SAD);
    expect($post->reactions(Reaction::SAD))->toBe(1);
});

it('user react to different to posts', function (): void {
    $user = User::factory()->create();;
    [$post1, $post2] = Post::factory(2)->create();

    $post1->react($user->id, Reaction::LIKE);
    expect($post1->reactions(Reaction::LIKE))->toBe(1);

    $post2->react($user->id, Reaction::SAD);
    expect($post2->reactions(Reaction::SAD))->toBe(1);
});

it('user react to different to the same posts', function (): void {
    $user = User::factory()->create();;
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(1);

    $post->react($user->id, Reaction::SAD);
    expect($post->reactions(Reaction::SAD))->toBe(1);
});

it('user toggle react posts', function (): void {
    $user = User::factory()->create();;
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(1);

    $post->react($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(0);
});

it('returns zero when no reactions are added', function (): void {
    $post = Post::factory()->create();

    expect($post->reactions(Reaction::LIKE))->toBe(0);
    expect($post->reactions(Reaction::SAD))->toBe(0);
});

it('does not allow duplicate reactions from same user', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);
    $post->react($user->id, Reaction::LIKE);

    expect($post->reactions(Reaction::LIKE))->toBe(0); // toggle off
});

it('allows user to change reaction type on same post', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(1);

    $post->react($user->id, Reaction::SAD);
    expect($post->reactions(Reaction::LIKE))->toBe(0);
    expect($post->reactions(Reaction::SAD))->toBe(1);
});

it('can count total reactions regardless of type', function (): void {
    [$user1, $user2, $user3] = User::factory(3)->create();
    $post = Post::factory()->create();

    $post->react($user1->id, Reaction::LIKE);
    $post->react($user2->id, Reaction::SAD);
    $post->react($user3->id, Reaction::LAUGH);

    expect($post->reactify->count())->toBe(3);
});

it('does not throw when unreacting a non-existent reaction', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $post->unReact($user->id, Reaction::LIKE);
    expect($post->reactions(Reaction::LIKE))->toBe(0);
});


it('tracks correct count after multiple toggles', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $post->react($user->id, Reaction::LOVE);
    expect($post->reactions(Reaction::LOVE))->toBe(1);

    $post->react($user->id, Reaction::LOVE);
    expect($post->reactions(Reaction::LOVE))->toBe(0);

    $post->react($user->id, Reaction::LOVE);
    expect($post->reactions(Reaction::LOVE))->toBe(1);
});
