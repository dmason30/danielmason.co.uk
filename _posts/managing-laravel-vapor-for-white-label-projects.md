---
title: 'Managing Laravel Vapor for White-Label Projects'
description: 'Discover how to effectively manage your Laravel Vapor setup for white-label projects. Our expert guide offers valuable insights and practical tips for optimizing your vapor setup, including moving to GitHub Actions, switching to the docker runtime, and implementing environment file encryption. With this comprehensive guide, you will be equipped to take your Laravel vapor setup to the next level.'
category: Laravel
author: 'danmason'
date: '2023-04-18 21:15'
image: 'https://danielmason.co.uk/assets/managing-laravel-vapor-for-white-label-projects/card.png'
---

I've been utilizing [Laravel Vapor](https://vapor.laravel.com/) since it was first released to deploy our white-label product to numerous AWS accounts and projects. However, Vapor has a one-to-one relationship between your code and a Vapor project, which led us to create workarounds to support our setup.

<!--more-->

Assuming you have a working knowledge of Vapor and how to configure and deploy it, in this post, I'll discuss our experience with Vapor and provide solutions to overcome these challenges. If you want to learn more about Vapor, check out the following resources:

* [Learn Laravel Vapor (video playlist) by Nuno Maduro](https://www.youtube.com/playlist?list=PLcjapmjyX17gqhjUz8mgTaWzSv1FPgePD)
* [Articles by Mohamed Said](https://divinglaravel.com/vapor)

## Defining a white-label product
A white-label product is an unbranded software (website/app) that can be sold under contract.
At my current job, we offer a white-label product that is hosted via Vapor but configured to
show the client's brand and style it to match their marketing websites.

From the beginning, we decided to isolate each client's data from one another, including
separating the test/staging database from the production database. To achieve this, we created one
nonprod AWS account for all clients for all test/staging environments, and one production AWS
account for each client. However, this approach does not align with how the Vapor service expects
you to structure your projects, which I'll discuss in the next section.

## Structuring Teams and Projects in the Laravel Vapor dashboard
Since each client has two AWS accounts, we needed to determine how to structure this in Vapor.
Fortunately, Vapor allows you to create unlimited teams, each with unlimited projects.
Additionally, you can add multiple AWS accounts to a team, and when creating a project within
that team, it asks you to select which AWS account it's for.

We have a `NONPROD` team which contains a project for each client, and each of those clients
has a test and staging environment:
![Vapor dashboard showing our nonprod projects setup](../assets/managing-laravel-vapor-for-white-label-projects/vapor-dashboard-nonprod.png)

Then for `production` we decided to create each client as a team, and each team would have a `prod` project.
Then inside that project there is a `production` environment that is deployed to the client's AWS account
with its private database, Redis cache, and NAT gateway.
![Vapor dashboard showing our production team and project setup](../assets/managing-laravel-vapor-for-white-label-projects/vapor-dashboard-prod.png)

Due to this setup, we now have different teams for each client, and our test/staging/prod environments have
different project IDs. This creates an issue when setting up the `vapor.yml`, which I will address
in the next section.

## The Vapor YAML File is restrictive
The `vapor.yml` file used to configure environments in Vapor is designed to support only one project,
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

We realized that we would need multiple `vapor.yml` files for each client and project.
When deploying a specific client, we would need to retrieve the appropriate vapor.yml for that client
and environment combination. Additionally, our solution would need to work well with our GitHub Actions
setup to automate deployments for all clients.

### Step 1: Create a repository for our vapor configs, encrypted environment files and dockerfiles
While it was tempting to add a new folder to our white-label code with separate Vapor configurations,
we didn't want to have to release that code every time we added a new client. Instead, we decided to
create a separate repository in GitHub with a clear folder structure.

```shell
$ tree -a
├── ABC
│   ├── .env.production.encrypted
│   ├── .env.staging.encrypted
│   ├── .env.test.encrypted
│   ├── production.yml
│   ├── staging.yml
│   └── test.yml
├── DEF
│   ├── .env.production.encrypted
│   ├── .env.staging.encrypted
│   ├── .env.test.encrypted
│   ├── production.yml
│   ├── staging.yml
│   └── test.yml
├── production.Dockerfile
├── staging.Dockerfile
└── test.Dockerfile
```

As you can see above we structured the repository in the same way as they are structured in Vapor. Identifying the
correct configurations to create or update are easy to find with this structure and will also help when trying to
identify which config to get when automating our deployment.

### Step 2: Deploy versioned Vapor configs to S3
With the vapor repository created in step 1 we decided that we wanted to be able to version the configs and deploy
them somewhere separate. This is to ensure that when GitHub Actions is running our automated deployment we are confident that
it is going to be using the correct configs and not accidentally deploy a new client when they are not ready yet.

We have experience deploying to S3 using GitHub Actions as the frontend of our application is a React SPA and is deployed to S3
with cloudfront. So we created a new S3 bucket in our central AWS account and put a GitHub Actions `deploy.yml` workflow file
in our vapor config repository.

```yaml
name: "Deploy to Cloud"

on:
  release:
    types: [published]

jobs:
  deploy:

    runs-on: ubuntu-latest

    env:
      TAG_NAME: ${{ github.event.inputs.tag }}
      CLIENT_REF: ${{ github.event.inputs.client }}
      BUILD_NUMBER: ${{ github.run_number }}
      COMMIT_HASH: ${{ github.sha }}

    name: ${{ github.event.release.tag_name }} (${{ github.run_number }})

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Upload to S3
        uses: jakejarvis/s3-sync-action@master
        with:
          args: --follow-symlinks --delete --exclude '.git/*' --exclude '.github/*' --exclude '.gitignore' --exclude '*.sh' --exclude '*.md' --exclude '.editorconfig'
        env:
          AWS_S3_BUCKET: vapor-client-configuration
          AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
          AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          AWS_REGION: 'eu-west-2'
```

Now, whenever we tag a new release GitHub Actions will automatically upload that version of the configs to our newly
created S3 bucket.

### Step 3: Create a `vapor:list` artisan command that lists all clients for a specific environment
All of our client configs that are now available on S3 are now our single source of truth for what clients we need to
deploy for a specific environment. We needed a command in our white-label repository that will output a json list for
GitHub Actions to use as a `matrix`, this will make more sense later when we explain how we use GitHub Actions to deploy
to all of our clients.

Running this command would output:
```shell
$ php artisan vapor:list production
{"include":["ABC","DEF"]}
```

### Step 4: Create a `vapor:deploy` artisan command for deploying clients
We also need a command that you can pass both a `{client}` and`{environment}` as arguments to download all of the required
configuration files and run the `vendor/bin/vapor deploy {environment}` command.

Running this command would output:
```shell
$ php artisan vapor:deploy production ABC
****************************************
*     Deploying to ABC production!     *
****************************************

Downloading vapor yaml config file (/ABC/production.yml).
Saving vapor yaml config file to base path.
Downloading file (production.Dockerfile).
Saving file to base path.
Downloading file (/ABC/.env.production.encrypted).
Saving file to base path.
Downloading environment file.
/home/runner/work/white-label-product/vendor/bin/vapor env:pull production
==> Downloading Environment File...
Environment variables written to [/home/runner/work/white-label-product/.env.production].
Running vapor deploy command!
Building project...
...
Project deployed successfully. (4m20s)
```

## Our GitHub Actions Deployment Workflow
Finally, we are ready to set up our GitHub Actions to run the commands we created in Step 3 and 4.

### Test & Staging deployment
Most people deploy test from a main or default branch, conversely, we group our changes as a release and when
we tag a release for test we will number it like so `2.7.1-beta.4`. The final number is essentially our release
candidate version, and we increase that number as we fix any bugs reported by the testers.

For the `test` environment we automatically deploy to all clients where the release tags contains `alpha`,
for example, `4.2.0-alpha.1`. For the `staging` environment we automatically deploy to all clients where the release tags does not contain `alpha`,
for example, `4.2.0-beta.1` or `4.2.0`.

**vapor-nonprod-deploy.yml**
```yaml
name: "Deploy Nonprod to Vapor"

on:
  release:
    types: [ published ]

defaults:
  run:
    working-directory: site

jobs:
  test:

    runs-on: ubuntu-latest

    outputs:
      clients: ${{ steps.clients.outputs.content }}
      environment: ${{ steps.environment.outputs.content }}

    env:
      AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
      AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
      TAG_NAME: ${{ github.event.release.tag_name }}
      BUILD_NUMBER: ${{ github.run_number }}
      COMMIT_HASH: ${{ github.sha }}

    name: Test and get clients to deploy

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ALLOW_EMPTY_PASSWORD: yes
          MYSQL_DATABASE: tmc_test
        ports:
          - 3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup Node.js 16.x
        uses: actions/setup-node@v3
        with:
          node-version: 16.x

      - name: Install yarn
        run: npm install -g yarn

      - name: Get yarn cache directory path
        id: yarn-cache-dir-path
        run: echo "dir=$(yarn cache dir)" >> $GITHUB_OUTPUT

      - name: Cache yarn dependencies
        uses: actions/cache@v3
        with:
          path: ${{ steps.yarn-cache-dir-path.outputs.dir }}
          key: dependencies-js-16.x-yarn-${{ hashFiles('**/yarn.lock') }}
          restore-keys: |
            dependencies-js-16.x-yarn-

      - name: Setup PHP 8.2
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          extensions: ctype, curl, date, dom, fileinfo, filter, gd, hash, iconv, intl, json, libxml, mbstring, openssl, pcntl, pcre, pdo, pdo_sqlite, pdo_mysql, phar, posix, simplexml, spl, sqlite, tokenizer, tidy, xml, xmlreader, xmlwriter, zip, zlib
          coverage: pcov

      - name: Get composer cache directory
        id: composer-cache
        run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

      - name: Cache composer dependencies
        uses: actions/cache@v3
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: dependencies-php-8.2-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            dependencies-php-8.2-composer-

      - name: Reset MySQL root user authentication method
        run: mysql --host 127.0.0.1 --port ${{ job.services.mysql.ports[3306] }} -uroot -e "alter user 'root'@'%' identified with mysql_native_password by ''"

      - name: Prepare Laravel Application
        run: cp .env.testing .env

      - name: Install PHP dependencies (composer)
        run: composer install --no-interaction

      - name: Install JavaScript dependencies (yarn)
        run: yarn --frozen-lockfile

      - name: Lint and test frontend code
        run: yarn test

      - name: Build JavaScript assets
        run: yarn production

      - name: Execute PHP tests
        run: composer test
        env:
          DB_HOST: 127.0.0.1
          DB_PORT: ${{ job.services.mysql.ports[3306] }}
          PHP_CS_FIXER_IGNORE_ENV: true

      - name: Get environment
        id: environment
        run: |
          if [[ ${{ contains(env.TAG_NAME, 'alpha') }} == true ]]; then
              echo "content=test" >> $GITHUB_OUTPUT
          fi
          if [[ ${{ contains(env.TAG_NAME, 'alpha') }} == false ]]; then
              echo "content=staging" >> $GITHUB_OUTPUT
          fi

      - name: Get clients to deploy
        id: clients
        run: |
          content=`php artisan vapor:list ${{ steps.environment.outputs.content }}`
          echo $content
          echo "content=$content" >> $GITHUB_OUTPUT

  deploy:
    needs: test
    runs-on: ubuntu-latest
    strategy:
      fail-fast: false
      matrix: ${{ fromJson(needs.test.outputs.clients) }}
      max-parallel: 10

    name: ${{ matrix.client }} / ${{ matrix.environment }} / ${{ github.event.release.tag_name }} (${{ github.run_number }})

    env:
      TAG_NAME: ${{ github.event.release.tag_name }}
      BUILD_NUMBER: ${{ github.run_number }}
      COMMIT_HASH: ${{ github.sha }}
      AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
      AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
      VAPOR_API_TOKEN: ${{ secrets.VAPOR_API_TOKEN }}

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ALLOW_EMPTY_PASSWORD: yes
          MYSQL_DATABASE: tmc_test
        ports:
          - 3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - name: Checkout code
        uses: actions/checkout@v3

      - name: Setup Node.js 16.x
        uses: actions/setup-node@v3
        with:
          node-version: 16.x

      - name: Install yarn
        run: npm install -g yarn

      - name: Get yarn cache directory path
        id: yarn-cache-dir-path
        run: echo "dir=$(yarn cache dir)" >> $GITHUB_OUTPUT

      - name: Cache yarn dependencies
        uses: actions/cache@v3
        with:
          path: ${{ steps.yarn-cache-dir-path.outputs.dir }}
          key: dependencies-js-16.x-yarn-${{ hashFiles('**/yarn.lock') }}
          restore-keys: |
            dependencies-js-16.x-yarn-

      - name: Setup PHP 8.2
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          extensions: ctype, curl, date, dom, fileinfo, filter, gd, hash, iconv, intl, json, libxml, mbstring, openssl, pcntl, pcre, pdo, pdo_sqlite, pdo_mysql, phar, posix, simplexml, spl, sqlite, tokenizer, tidy, xml, xmlreader, xmlwriter, zip, zlib
          coverage: pcov

      - name: Get composer cache directory
        id: composer-cache
        run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

      - name: Cache composer dependencies
        uses: actions/cache@v3
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: dependencies-php-8.2-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            dependencies-php-8.2-composer-

      - name: Reset MySQL root user authentication method
        run: mysql --host 127.0.0.1 --port ${{ job.services.mysql.ports[3306] }} -uroot -e "alter user 'root'@'%' identified with mysql_native_password by ''"

      - name: Prepare Laravel Application
        run: cp .env.testing .env

      - name: Install PHP dependencies (composer)
        run: composer install --no-interaction

      - name: Install JavaScript dependencies (yarn)
        run: yarn --frozen-lockfile

      - name: Get last commit message
        id: last-commit-message
        run: echo "value=$(git log -1 --pretty=format:"%s")" >> $GITHUB_OUTPUT

      - name: Set package version
        run: yarn set-version "$TAG_NAME"

      - name: Deploy to ${{ matrix.client }} ${{ matrix.environment }}
        run: php artisan vapor:deploy ${{ matrix.environment }} ${{ matrix.client }} --tag="$TAG_NAME" --commit="$COMMIT_HASH" --message="${{ steps.last-commit-message.outputs.value }}"
        env:
          DB_HOST: 127.0.0.1
          DB_PORT: ${{ job.services.mysql.ports[3306] }}
```

You will notice that we use the output of the `vapor:list` command as the `matrix` of the `deploy` step, this
will display like so in GitHub Actions and each client is deployed asynchronously:
![GitHub Actions deploying a test environment](../assets/managing-laravel-vapor-for-white-label-projects/vapor-deploy-nonprod.png)

### Production deployment
We manually deploy to production using the GitHub Actions `workflow_dispatch` hook. This allows us to define
fields for a form as shown below:
![Manually deploying a production environment in a GitHub Action](../assets/managing-laravel-vapor-for-white-label-projects/vapor-deploy-prod.png)

This is then passed to the GitHub Actions workflow as inputs and we pass the `Client Reference` and the `Release tag`
to the `vapor:deploy` command as `{client}` and `{environment}` arguments. The manual workflow also handles manually
deploying `test`, `staging` and `production` releases.

**vapor-manual-deploy.yml**
```yaml
name: "Manual Deploy to Vapor"

on:
  workflow_dispatch:
    inputs:
      client:
        description: "Client reference"
        required: true
      tag:
        description: "Release tag"
        required: true

jobs:
  test:

    runs-on: ubuntu-latest

    env:
      TAG_NAME: ${{ github.event.inputs.tag }}
      CLIENT_REF: ${{ github.event.inputs.client }}
      BUILD_NUMBER: ${{ github.run_number }}
      COMMIT_HASH: ${{ github.sha }}

    name: ${{ github.event.inputs.client }} / ${{ github.event.inputs.tag }} (${{ github.run_number }})

    defaults:
      run:
        working-directory: site

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ALLOW_EMPTY_PASSWORD: yes
          MYSQL_DATABASE: tmc_test
        ports:
          - 3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - name: Checkout code
        uses: actions/checkout@v3
        with:
          ref: 'refs/tags/${{ env.TAG_NAME }}'

      - name: Setup Node.js 16.x
        uses: actions/setup-node@v3
        with:
          node-version: 16.x

      - name: Install yarn
        run: npm install -g yarn

      - name: Get yarn cache directory path
        id: yarn-cache-dir-path
        run: echo "dir=$(yarn cache dir)" >> $GITHUB_OUTPUT

      - name: Cache yarn dependencies
        uses: actions/cache@v3
        with:
          path: ${{ steps.yarn-cache-dir-path.outputs.dir }}
          key: dependencies-js-16.x-yarn-${{ hashFiles('**/yarn.lock') }}
          restore-keys: |
            dependencies-js-16.x-yarn-

      - name: Setup PHP 8.2
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
          extensions: ctype, curl, date, dom, fileinfo, filter, gd, hash, iconv, intl, json, libxml, mbstring, openssl, pcntl, pcre, pdo, pdo_sqlite, pdo_mysql, phar, posix, simplexml, spl, sqlite, tokenizer, tidy, xml, xmlreader, xmlwriter, zip, zlib
          coverage: pcov

      - name: Get composer cache directory
        id: composer-cache
        run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

      - name: Cache composer dependencies
        uses: actions/cache@v3
        with:
          path: ${{ steps.composer-cache.outputs.dir }}
          key: dependencies-php-8.2-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            dependencies-php-8.2-composer-

      - name: Reset MySQL root user authentication method
        run: mysql --host 127.0.0.1 --port ${{ job.services.mysql.ports[3306] }} -uroot -e "alter user 'root'@'%' identified with mysql_native_password by ''"

      - name: Prepare Laravel Application
        run: cp .env.testing .env

      - name: Install PHP dependencies (composer)
        run: composer install --no-interaction

      - name: Migrate database
        run: php artisan migrate --force
        env:
          DB_HOST: 127.0.0.1
          DB_PORT: ${{ job.services.mysql.ports[3306] }}

      - name: Get last commit message
        id: last-commit-message
        run: echo "value=$(git log -1 --pretty=format:"%s")" >> $GITHUB_OUTPUT

      - name: Install JavaScript dependencies (yarn)
        run: yarn --frozen-lockfile

      - name: Set package version
        run: yarn set-version "$TAG_NAME"

      - name: Deploy to Test
        if: contains(env.TAG_NAME, 'alpha')
        env:
          DB_HOST: 127.0.0.1
          DB_PORT: ${{ job.services.mysql.ports[3306] }}
          AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
          AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          VAPOR_API_TOKEN: ${{ secrets.VAPOR_API_TOKEN }}
        run: php artisan vapor:deploy test $CLIENT_REF --tag="$TAG_NAME" --commit="$COMMIT_HASH" --message="${{ steps.last-commit-message.outputs.value }}"

      - name: Deploy to Stage
        if: contains(env.TAG_NAME, 'beta')
        env:
          DB_HOST: 127.0.0.1
          DB_PORT: ${{ job.services.mysql.ports[3306] }}
          AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
          AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          VAPOR_API_TOKEN: ${{ secrets.VAPOR_API_TOKEN }}
        run: php artisan vapor:deploy staging $CLIENT_REF --tag="$TAG_NAME" --commit="$COMMIT_HASH" --message="${{ steps.last-commit-message.outputs.value }}"

      - name: Deploy to Prod
        if: contains(env.TAG_NAME, 'alpha') == false && contains(env.TAG_NAME, 'beta') == false
        env:
          DB_HOST: 127.0.0.1
          DB_PORT: ${{ job.services.mysql.ports[3306] }}
          AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
          AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          VAPOR_API_TOKEN: ${{ secrets.VAPOR_API_TOKEN }}
        run: php artisan vapor:deploy production $CLIENT_REF --tag="$TAG_NAME" --commit="$COMMIT_HASH" --message="${{ steps.last-commit-message.outputs.value }}"
```

## Conclusion
Here's a summary of the article so far. We use Laravel Vapor to deploy our white-label product to multiple AWS accounts
and projects. However, Vapor only supports a one-to-one relation between your code and a Vapor project. Our setup with
multiple AWS accounts per client and separation of databases did not align with how Vapor expects you to structure your
projects. So, we had to find a workaround.

We created separate repositories on GitHub with different Vapor configurations for each client. This solution required
a way to download the specific configuration for a client and environment. We solved this problem by creating a
`vapor:list` Artisan command to list all clients for a specific environment.

Although this solution is not perfect, it was a fun problem to solve. If you have a better solution, feel free to reach
out to me on [Twitter](https://twitter.com/danmasonmp).
