---
title: 'Making the most of your Laravel dumps'
description: 'Laravel has a number of convenient methods to help speed up your development. In this post, I will show you the benefits of dump and some fluent APIs that have this wonderful function available.'
category: Laravel
author: 'danmason'
date: '2020-09-02 18:23'
image: 'https://danielmason.co.uk/assets/making-the-most-of-your-laravel-dumps/card.png'
---

Laravel has a number of convenient methods to help speed up your development. When developing you sometimes need to
see what an `object` or `variable` contains to make sure the code is doing what you expect. In this post, I will
show you what mastering your dumps can do and showcase some fluent APIs that have this wonderful function available.

<!--more-->

## Just tell me what a dump is!
All puns aside, I am sure most reading this will have used `dd` or `dump` at some point, so I will keep this brief
, but for those that don't know it is an alternative to using the traditional `var_dump` or `die` as a way to output
 the contents of some variables or objects. Take the below example code:

 ```php
$data = ['foo' => 'bar'];

dump($data);
 ```

If running on the command line this will output to your console like the below.
```shell script
array:1 [
  "foo" => "bar"
]
```

## Query Builder SQL and bindings
When you are building a complex query with Eloquent's fluent API then it could be handy to see what the query looks
like as you build it. Now a few years ago to do this I would have the done this to get the query and bindings displayed:

```php
$query = App\Models\Post::where('author', 'Bob');
dump($query->toSql(), $query->getBindings());
```

Which would output the below into your console showing the fully built query and the bindings underneath.
```shell script
select * from `posts` where `author` = ?

array:1 ["Bob"]
```

Now this got even better in [Laravel 5.8](https://laravel.com/docs/5.8/queries#debugging) when they added the `dump
` and `dd` methods to the query `Builder` class. Now you can just chain the `dump` method onto the end of your model
query, and you will get the same output. The method is chainable too, so you can build your query in stages and dump
at each stage if you really needed too.

 ```php
 App\Models\Post::where('author', 'Bob')
     ->dump()
     ->where('type', 'news')
     ->dump();
 ```

## Collections
Once you are happy with your query then you can finally get your results, but wait... oh no they are not what you were
expecting. Fortunately, Laravel is here to save the day yet again with yet another fluent `dump` :eyes:. If you call
the `dump` function on an Eloquent `Collection` you will see it output any array of models:

```php
App\Models\Post::where('author', 'Bob')->get()->dump();
```

```shell script
array:2 [
  0 =>  App\Models\Post {#1745
      #attributes: array:7 [
          "id" => 1
          "title" => "Poop is a crap palindrome..."
          "author" => "Bob"
          # ...
      ]
  },
  1 =>  App\Models\Post { }
]
```

Additionally, you can also dump out your collections created from normal arrays after you have finished manipulating
them. See the example below:

```php
collect([
    ['color' => 'red'],
    ['color' => 'green'],
    ['color' => 'blue'],
])->pluck('color')->dump();
```

```shell script
array:3 ["red", "green", "blue"]
```

## Rendered dumps on requests
When you call any of the above `dump` methods in a `Controller` method or `Route` closure Laravel will render the
output for you. If the dump content is a multidimensional `array` or `Collection` then it will provide an expandable
rendered output which makes navigating large values easier.

```php
Route::get('dump', function () {
    App\Models\Post::limit(20)->dump();
});
```

```shell script
array:20 [▼
  0 => App\Models\Post {#3380 ▶}
  1 => App\Models\Post {#3381 ▶}
  ...
]
```

## Wrapping up
These are the current dumps available to you when using Laravel every day. There are plenty of tools
and packages out there specifically created to help you in debugging your developments (see below) but sometimes it
can be simple enough to just quickly call the `dump` function to get inline output.

* [Telescope](https://github.com/laravel/telescope)
* [Tinkerwell](https://tinkerwell.app)
* [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)

Thank you very much for reading this far, come find me on Twitter if you want to let me know if I missed something or
to talk anything Laravel or software development with me.
