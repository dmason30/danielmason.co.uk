---
title: 'Deploying the same code to multiple Laravel Vapor projects'
description: 'I have been using Laravel Vapor since it was launched at our company to deploy our white-label product to multiple different aws accounts and projects. The problem is that when you configure vapor it has a one-to-one relation between your code, and a vapor project. As a result we had to do some workarounds to support our setup.'
category: Laravel
author: 'danmason'
date: '2020-09-04 11:45'
image: 'https://danielmason.co.uk/assets/deploying-the-same-code-to-multiple-vapor-projects/card.png'
---

I have been using [Laravel Vapor](https://vapor.laravel.com/) since it was launched at our company to deploy our
white-label product to multiple different aws accounts and projects. The problem is that when you configure vapor it
has a one-to-one relation between your code, and a vapor project. As a result we had to do some workarounds to
support our setup.

<!--more-->

> I have written an updated version of this article here: [Managing Laravel Vapor for White-Label Projects](/posts/managing-laravel-vapor-for-white-label-projects)

In this post I am going to assume you have working knowledge of vapor and how you configure and deploy it. If you
want to read up on Vapor first then please check out the below resources.

* [Taylor Otwell - Introducing Laravel Vapor (video)](https://youtu.be/XsPeWjKAUt0)
* [Articles By Mohamed Said (Laravel Employee)](https://divinglaravel.com/vapor)

## What is a white-label product?
I understand that different countries may have a different term for this. At my current work we have created an
unbranded software (website/app) that can be purchased under contract. Once the contract has been agreed, we set them
up with the site hosted via Vapor but configured to show their brand and style it to match their marketing websites.

With each client we also decided from the start that their data must be isolated from each other including separating
the test/stage database from the production database. In order to make this happen, for each client we have one nonprod
AWS account and one production AWS account. This does not easily align with how the Vapor service expects you to
structure your projects which I will discuss in the next section.

## Our teams and projects setup
Since each of our clients have two AWS accounts each we needed to consider how we structured this in vapor which
generously lets you create unlimited teams and each team can have unlimited projects. In addition, you can add
multiple AWS accounts to a team and then when creating a project within that team it asks you to select which AWS
account it is for.

![Vapor dashboard showing our teams and projects setup](../assets/deploying-the-same-code-to-multiple-vapor-projects/vapor-dashboard.png)

We decided that each client will be created as a team and then each team will have two projects
`nonprod` and `prod`. In the `nonprod` environment we have a test environment and that will be deployed to that
clients `nonprod` AWS account with its own private database, redis cache and NAT gateway. The `prod` environment is
similar in that it has a production environment that will be deployed to the clients `prod` AWS account.

So because of this setup we now have different teams for each client, and our test/prod environments have different
project ids. This now causes an issue when trying to set up the `vapor.yml` which I attempt to resolve in the next
section.

## Handling the vapor configuration file
The `vapor.yml` file that is used to configure your environments is design to only support one project which is
specified by the `id` and `name` fields:

 ```yaml
id: 2
name: vapor-laravel-app
environments:
    production:
        # config here...
    test:
        # config here...
 ```

We came to the realisation that we are going to have multiple `vapor.yml` files for each client and
one foreach project. Then when we are deploying a specific client we need to get the right `vapor.yml` for that
`client + envrionment` combination. Additionally, our solution for this would have to work well in our Travis CI to
automate the deployments of all clients.

### Step 1: Create a repository for our vapor configs
While it was tempting to add a new folder to our white-label code with the separate vapor configs in we didn't want
to have to do a release of that code every time we wanted to add a new client. So we decided to create a separate
repository in GitHub with an easy to understand folder structure.

```shell script
danmason@Dans-MacBook-Pro vapor-client-configuration % tree
├── CRU
│   ├── nonprod
│   │   └── vapor.yml
│   └── prod
│       └── vapor.yml
├── TRI
│   ├── nonprod
│   │   └── vapor.yml
│   └── prod
│       └── vapor.yml
```

As you can see above we structured the repository in the same way as they are structured in Vapor. Identifying the
correct configurations to create or update are easy to find with this structure and will also help when trying to
identify which config to get when automating our deployment.

### Step 2: Deploy versioned Vapor configs to S3
With the vapor repository created in step 1 we decided that we wanted to be able to version the configs and deploy
them somewhere separate. This is to ensure that when Travis is running our automated deployment we are confident that
it is going to be using the correct configs and not accidentally deploy a new client when they are not ready yet.

We have experience deploying to S3 using Travis as the frontend of our application is a React SPA and is deployed to S3
with cloudfront. So we created a new S3 bucket in our central AWS account and put a `.travis.yml` in our vapor config
repository. Now, whenever we tag a new release Travis will automatically upload that version of the configs to our newly
created S3 bucket.

### Step 3: Create a command to download the config for deployment
Now we have all of our client configs available on S3 we needed to add a command to our white-label repository
that will download a specific config for a certain client and specific environment. Fortunately, Laravel makes
getting files from the cloud very easy. Our first naive approach to this was a simple command that downloads a config
file:

```php
protected $signature = 'vapor:config:download {client} {project}';

public function handle(): int
{
    $path = sprintf('%s/%s/vapor.yml', $this->argument('client'), $this->argument('project'));

    if (Storage::cloud()->exists($path)) {
        $file = Storage::cloud()->get($path);
        file_put_contents(base_path('vapor.yml'), $file);
        $this->info('Vapor config downloaded and saved to base directory!');
        return 0;
    }

    $this->error('File not found: ' . $path);
    return 1;
}
```

This did what it should, but it wasn't really what we needed as we wanted to be able to deploy all clients in one go
from within Travis. We changed the command so that now you would only need to add an environment argument. It would
download the config similar to the command above and afterwards run the vapor deploy command. This way it will be
able to deploy all clients from within Travis. Below is a simplified version of the code we use now:

```php
protected $signature = 'vapor:deploy {environment}';

public function handle(): int
{
    $environment = $this->argument('environment');
    $project = $environment === 'production' ? 'prod' : 'nonprod';
    $contents = Storage::cloud()->listContents('/');

    collect($contents)
        ->whereStrict('type', 'dir')
        ->pluck('path')
        ->flatten()
        ->each(function (string $client) use ($project, $environment) {
            $path = sprintf('%s/%s/vapor.yml', $client, $project);
            $file = Storage::cloud()->get($path);
            file_put_contents(base_path('vapor.yml'), $file);
            $this->info("$client Vapor config downloaded and saved to base directory!");

            $this->info("Executing vapor deploy command for $client!");
            passthru(base_path('vendor/bin/vapor') . ' deploy ' . $environment, $exitCode);

            $exitCode
                ? $this->error("$client deployment failed!")
                : $this->info("$client deployment succeeded!");

            $this->exitCode = $this->exitCode ?: $exitCode;
        });

    return $this->exitCode;
}
````

This code deploys all clients that have a directory in the S3 bucket we set up in Step 2. If one client fails to
deploy it will still continue and try to deploy the other clients, however, the command will return an error exit
code to indicate that something went wrong. This has made deploying to all our clients a breeze and shows that
even if a service has its limits you can still find nice and clean ways to work around them.

One thing I will probably change is the naming of the projects for `nonprod` as we used to have both a test and
staging environment in the `nonprod` project, hence the name. To save costs, now we decided to stop using a staging
environment and as a result our project naming doesn't quite make as much sense anymore.

### Step 4: Deploying our white label product to each client
Finally, we are ready to set up our Travis CI to run the command we created in Step 3. Travis CI allows you to
configure multiple deployments which is great as we need one each for test and production. Our `.travis.yml` deploy
steps look like this:

```yaml
deploy:
  # TEST
  - provider: script
    skip_cleanup: true
    script: php artisan vapor:deploy test
    on:
      tags: true
      condition: $TRAVIS_TAG =~ ^[0-9]+\.[0-9]+\.[0-9]+-beta\.[0-9]+$
      all_branches: true
  # PROD
  - provider: script
    skip_cleanup: true
    script: php artisan vapor:deploy production
    on:
      tags: true
      condition: $TRAVIS_TAG =~ ^[0-9]+\.[0-9]+\.[0-9]+$
      all_branches: true
```

Most people deploy test from a main or default branch, conversely, we group our changes as a release and when
we tag a release for test we will number it like so `2.7.1-beta.4`. The final number is essentially our release
candidate version, and we increase that number as we fix any bugs reported by the testers.

As you can see in the travis config above we do a regex check on the `TRAVIS_TAG`. If it contains beta then it will
only run the test deployment, however, when it is a production release we will just tag it with a normal version
(e.g. `2.7.1`) and Travis will only run the production deploy as it matches the regex check.

## The End
I know this was a lot of information so good effort if you have made it this far. This solution is not perfect,
but it was fun problem to solve. Although, we only had a few days to figure it out so if you have a better solution
do let me know on [Twitter](https://twitter.com/danmasonmp).

I wrote a lot about Travis in this post, but we may decide to move to GitHub Actions CI soon so that code and CI are
in the same place. You will see that I use Actions in [my open source projects](https://github.com/fidum) and in some
side projects at work so it shouldn't be too bad. It's just converting the deployment steps that will be the tricky
bit.
