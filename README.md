# Blog Code Test

This repo provides a code test example in the form of a basic blog. 

#### Requirements
 - PHP 8.0+
 - Mysql 8.0

#### Installation
``` shell
git clone https://github.com/ShamarKellman/code-test-blog.git
cd code-test-blog
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan schedule:work
```

#### Tables
 - users
   - id (biginteger)
   - name (string)
   - username (string)
   - email (string)
   - password (string)
 - posts
   - id (uuid)
   - title (string)
   - description (longtext)
   - image (string)
   - user_id (foreignId)
 - likes
   - user_id (foreignId)
   - post_id (foreignUuid)

#### Models
 - User
 - Post
 - Like

#### Controllers
 - Auth\LoginController
 - Auth\RegisterController
 - PostController
 - LikeController

#### Request
 - LoginRequest
 - RegisterRequest
 - StorePostRequest

#### Resources
 - LikeResource
 - PostResource
 - UserResource

#### Test
 - Auth Test
   - users can login
   - users can not authenticate with invalid password
   - new users can register

 - Posts Test
   - user can create post
   - user cannot create post if not authenticated
   - user can view all post
   - user can view by id
   - user can like a post
   - user can unlike a post
   - user can remove a post
   - user cannot remove a post if they do not own the post
   - can remove post after 15 days
   - sends notification to all users when post is created

#### Events
 - NewPostCreatedEvent.php

#### Listeners
 - SendNewPostCreatedEmailListener

#### Notifications
 - NewPostCreatedNotification

#### Routes
 - (POST)       api/v1/login
 - (GET)        api/v1/posts
 - (POST)       api/v1/posts
 - (GET)        api/v1/posts/{post}
 - (DELETE)     api/v1/posts/{post}
 - (POST)       api/v1/posts/{post}/like
 - (POST)       api/v1/posts/{post}/unlike
 - (POST)       api/v1/register
 - (GET)        sanctum/csrf-cookie

#### Notes
 - `php artisan model:prune` is scheduled to run daily (see: app/Console/Kernel)  
 - Uses Easy Coding Standard for PHPCS and PHPCS Fixer - `composer check` and `composer fix`
 - Static Analysis using Larastan up to level 6 `composer analyse`
 - Testing uses Pest `composer test`
 - Uses GitHub Workflows (see .github/workflows folder)
