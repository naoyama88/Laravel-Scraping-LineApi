# Laravel-Scraping-LineApi
|Screen shot 1|Screen shot 2|
|---|---|
|![](https://user-images.githubusercontent.com/15808541/56231258-e51c9e00-6032-11e9-85b8-febc45a33d55.png)|![](https://user-images.githubusercontent.com/15808541/56231227-d0d8a100-6032-11e9-92a7-6155b29831e5.png)|

## 日本人ユーザーの方へ

### これはなにか
- JPカナダの新着バンクーバー求人情報をLINEに流してくれるBotアプリケーションです。
    - こちらから取得します。　http://bbs.jpcanada.com/listing.php?bbs=4&order=2
    - ラインにメッセージを送ります。（メールサービスは現在停止中）
    - JPカナダ上に投稿されたバンクーバーのお仕事情報をいち早く手に入れたい方におすすめです。

### 使い方
- LINE上でこのボットと友達になってください。

<img src="https://user-images.githubusercontent.com/15808541/62243205-a87e7380-b391-11e9-9f0f-1325747e3445.png" width="240">

- バンクーバー時間で朝8時から夜11時の間に、お仕事情報がこのボットから通知されます。
- 通知からJPカナダのサイトに飛んでお仕事の詳細情報を確認できます。

## For users

### What is this
- Web application
    - scrape from http://bbs.jpcanada.com/listing.php?bbs=4&order=2
    - send message
        - LINE
        - mail (now it does not work because something wrong happened with SendGrid and heroku)

### Who this is for
- For users who want to get job information in vancouver posted on jpcanada as soon as possible

### How to use (for users)
- Be friends with the bot on LINE

<img src="https://user-images.githubusercontent.com/15808541/62243205-a87e7380-b391-11e9-9f0f-1325747e3445.png" width="240">

- Or register the email address (not in service now)
    - You can get job information posted on jpcanada as soon as possible

## For developers

### What I use
- heroku (free)
- PHP 7.3
- Laravel 5.5
    - use Laravel-LINEApiBot model from this website
    - https://qiita.com/sh-ogawa/items/2238e579d7ee538025a0
- specific PHP libraries
    - phpQuery
    - SendGrid
    - line-bot-sdk
- heroku add ons
    - heroku Scheduler
    - heroku SendGrid
    - heroku Postgres

### How to run on your server
- (This app depends on Heroku, but I want to enhance not to depend on heroku)
- create heroku account
- create LINE developer account
- clone this repo
- set up
    - composer install
    - migrate DB
    - setting on heroku
    - make .env file (if using heroku, set VARS on heroku (heroku config) )

### Other
- This app definitely does not obstruct the website jpcanada
    - 2 or 3 times access per an hour
    - access to jpcanada between 35 and 46 times in a day (depends on the heroku scheduler)
    - This does not access jpcanada during midnight (11pm to 8am)

### Test code
- making now