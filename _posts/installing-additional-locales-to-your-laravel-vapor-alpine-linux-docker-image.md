---
title: 'Installing additional locales to your Laravel Vapor Alpine Linux Docker image'
description: 'Explore the step-by-step process of integrating locales into your Laravel Vapor environment. This guide focuses on the essentials of adding locales to your Dockerfile, ensuring compatibility for a range of locale-based functionalities'
category: Laravel
author: 'danmason'
date: '2023-12-08 14:06'
image: 'https://danielmason.co.uk/assets/installing-additional-locales-to-your-laravel-vapor-alpine-linux-docker-image/card.png'
---

Alpine Linux only comes with an english locale by default. If you want to use the php intl extension for formatting dates, numbers and more based on different locales then this blog post will show you how to
a simple way to install all locales on your [Laravel Vapor](https://vapor.laravel.com/) deploys.

<!--more-->

## When do you need this?
Installing these locales is useful if you require use of the [intl extension](https://www.php.net/manual/en/book.intl.php) for formatting various strings, sorting results based on locale and it supports so many other locale
based functionality.

For example, in [Laravel 10.33.0](https://github.com/laravel/framework/releases/tag/v10.33.0) a new `Number` utility class was added:

```php
use Illuminate\Support\Number;

$number = Number::format(100000.123);
// 100,000.123

$number = Number::format(100000.123, locale: 'de');
// 100.000,123
```

See the [Laravel Number Helper docs](https://laravel.com/docs/10.x/helpers#numbers) for more info.

This uses the intl extensions's [NumberFormatter](https://www.php.net/manual/en/class.numberformatter.php) class under the hood. However, without the locales installed on vapor then the `de` example above would not have been
formatted correctly as any locale except `en-US` is missing by default.

## Requirements
To use this solution you must be using in `runtime: docker` or `runtime: docker-arm` in your `vapor.yml` so that you are able to provide a custom `<environment>.Dockerfile`.
See the Vapor docs [Docker Runtimes](https://docs.vapor.build/projects/environments.html#docker-runtimes) section for more information.

## Installing the locales
In your `Dockerfile` you should add the following line between the `RUN` and `COPY` lines

```dockerfile
RUN apk add --no-cache icu-data-full
```

Afterwards your changed `Dockerfile` should look something like this:
```dockerfile
FROM laravelphp/vapor:php82-arm

# Install locales as alpine only has english by default.
# Used for locale based formatting using the intl extension.
RUN apk add --no-cache icu-data-full

# Place application in Lambda application directory...
COPY . /var/task
```

## Conclusion
This is the best solution I have found so far for this. If you have a better solution, feel free to reach out to me on [Twitter](https://twitter.com/danmasonmp).
