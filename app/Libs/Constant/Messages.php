<?php

namespace App\Libs\Constant;

class Messages
{
    const NOT_SUPPORTED_OPERATE   = 'その操作はサポートしておりません。';
    const ADD_FRIEND              = '友達登録ありがとうございます！JPCANADA(Vancouver)のお仕事情報をお伝えするアカウントです。' . PHP_EOL . PHP_EOL
        . '１）このアカウントは、JPCANADAのバンクーバー仕事・求人掲示板に新規投稿された投稿を順次お知らせします。' . PHP_EOL
        . '２）既存の投稿にコメントがついた場合でもこちらのアカウントには流れてくることはありませんのでご了承ください。' . PHP_EOL
        . '３）お仕事が追加され次第順次お知らせするため通知が多くなる場合があります。煩わしいと感じる場合には当アカウントをミュートに設定してご利用ください。' . PHP_EOL
        . '４）特定の文字をメッセージすると、その文字をタイトルに含むお仕事情報を過去1ヶ月のお仕事情報から取得し提供します。' . PHP_EOL . PHP_EOL
        . '注意' . PHP_EOL
        . '１）当アカウントは非公式ですので、JPCANADAが公開する一切の情報に関して関与しておりませんのでご注意ください。' . PHP_EOL
        . '２）当アカウントを利用したことによってユーザーが負った不利益について、当アカウントは一切の責任を負いません。' . PHP_EOL
        . '３）当サービスの管理者は金銭的収入を得ることは基本的にありませんが、ごく稀にお仕事情報以外の情報をアナウンスする場合がございます。ご了承ください。' . PHP_EOL
        . '４）当サービスは予告なく終了する場合がございます。ご注意ください。';
    const ADD_FRIEND_ERROR        = '友達登録ありがとうございます。不具合が発生しました。';
    const WRONG_SEND_TYPE = '送信タイプ異常';
    const MAIL_CONTEXT_DATE_FORMAT = 'Y年n月j日';

    // using sprintf
    const MAIL_HEADER_1 = '<h3>JPCANADA <br>仕事・求人＠バンクーバー</h3>';
    const MAIL_HEADER_2 = '<h3>本日%sに追加されたお仕事です。</h3><br>';
}