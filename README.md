# Reactify an easy way to add likes to comment, photos, etc.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phpdominicana/reactify.svg?style=flat-square)](https://packagist.org/packages/phpdominicana/reactify)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/phpdominicana/reactify/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/phpdominicana/reactify/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/phpdominicana/reactify/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/phpdominicana/reactify/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/phpdominicana/reactify.svg?style=flat-square)](https://packagist.org/packages/phpdominicana/reactify)

Add reactions (like 👍, dislike 👎, love ❤️, haha 😄, etc.) to any Eloquent model in Laravel. Perfect for blogs, comments, posts, reviews, and more.

## Installation

You can install the package via composer:

```bash
composer require phpdominicana/reactify
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="reactify-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="reactify-config"
```

## Usage

### Add the trait to the model you want to add reactions.
```php
use App\Traits\HasReactions;

class Post extends Model
{
    use HasReactions;
}
```
### Sample usage

```php
$post->react($userId, Reaction::LIKE);
$post->unReact($userId, Reaction::LIKE);
$post->reactions(Reaction::LIKE); // returns integer count
```
Get total reactions for all types

```php
$post->reactionCounter; // returns a ReactionCounter model with all counts
```
### Available Reaction Types
You can define your own reactions via constants:

```php
enum Reaction: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case LOVE = 'love';
    case ANGRY = 'angry';
    case SAD = 'sad';
    case HAPPY = 'happy';
    case WOW = 'wow';
    case CARE = 'care';
    case THANKFUL = 'thankful';
    case SUPPORT = 'support';
    case LAUGH = 'laugh';
    case CONFUSED = 'confused';
    case HUG = 'hug';
    case KISS = 'kiss';
    case SMILE = 'smile';
    case HEART = 'heart';
    case STAR = 'star';
    case THUMBS_UP = 'thumbs_up';
    case THUMBS_DOWN = 'thumbs_down';
    case CLAP = 'clap';
    case BRAVO = 'bravo';
    case CELEBRATE = 'celebrate';
    case EYES = 'eyes';
    case HANDSHAKE = 'handshake';
    case PRAY = 'pray';
    case SALUTE = 'salute';
    case TROPHY = 'trophy';
    case WELCOME = 'welcome';
    case WINK = 'wink';
    case YUMMY = 'yummy';
    case COOL = 'cool';
    case DROOL = 'drool';
    case FLOWER = 'flower';
    case GIFT = 'gift';
    case GOOD = 'good';
    case HIGH_FIVE = 'high_five';
    case HUGS = 'hugs';
    case KISSES = 'kisses';
    case LOVE_YOU = 'love_you';
    case MISS_YOU = 'miss_you';
    case NO = 'no';
    case OK = 'ok';
    case OK_HAND = 'ok_hand';
    case PARTY = 'party';
    case PEACE = 'peace';
    case PIZZA = 'pizza';
    case PRIDE = 'pride';
    case QUESTION = 'question';
    case RAINBOW = 'rainbow';
    case ROCKET = 'rocket';
    case SICK = 'sick';
    case SORRY = 'sorry';
    case SPARKLES = 'sparkles';
    case STRONG = 'strong';
    case SUN = 'sun';
    case THANK_YOU = 'thank_you';
    case THINKING = 'thinking';
}
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [PHP Dominicana](https://github.com/elminson)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
