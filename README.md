# covid
COVID related code.

## Features
* Using ArcGIS to post confirmed covid cases to Telegram
* Using DIVI Intensivregister to post hospital occupance to Telegram

## Setup
* Copy `./src/.env.example` to `./src/.env`
* `php artisan key:generate`
* Add your Telegram bot token at `TELEGRAM_BOT_TOKEN`
* Add your Telegram chat ID at `TELEGRAM_CHAT_ID`
  * To get the chat ID, follow the steps below

### Get your Telegram chat ID

Start a group chat with your bot, write a message and open `https://api.telegram.org/bot<TELEGRAM_BOT_TOKEN></bot_token>/getUpdates`

Use the chat ID located in JSON at `result.[0].message.chat.id
```json
{
    "ok":true,
    "result":[
        {
            "update_id":926672828,
            "message":{
                "message_id":117,
                "from":{
                    ...
                },
                "chat":{
                    "id":815324966,
                    ...
                },
                "date":1659126094,
                "text":"test"
            }
        }
    ]
}
```

## Usage

Implemented commands:
* `php artisan notify:about-confirmed-cases`
* `php artisan notify:about-hospitals`