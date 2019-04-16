# naoyama88-jcl

<img src="https://user-images.githubusercontent.com/15808541/56231258-e51c9e00-6032-11e9-85b8-febc45a33d55.png" alt="drawing" width="200"/>
<img src="https://user-images.githubusercontent.com/15808541/56231227-d0d8a100-6032-11e9-92a7-6155b29831e5.png" alt="drawing" width="200"/>

## For users

### What is this
- Web application
    - scrape http://bbs.jpcanada.com
    - send message
        - LINE
        - mail (now it does not work because something wrong happened with SendGrid and heroku)

### Who this is for
- For users who want to know the job information posted on jpcanada as soon as possible

### How to use (for users)
- Be friends with the bot on LINE
- Or register the email address (not in service now)
    - You can get job information posted on jpcanada as soon as possible

## For developers

### What I use
- heroku (free)
- PHP 7.3
- Laravel 5.5
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
- maybe that's it

### Other
- This app definitely does not obstruct the website jpcanada
    - 2 or 3 times access per an hour
    - access to jpcanada between 35 and 46 times in a day (depends on the heroku scheduler)
    - not to access jpcanada during midnight (11pm to 8am)
