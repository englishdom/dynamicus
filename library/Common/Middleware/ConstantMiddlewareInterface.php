<?php

namespace Common\Middleware;

/**
 * Class ConstantMiddlewareInterface
 * @package Audicus\Middleware
 */
interface ConstantMiddlewareInterface
{
    const HASH = 'hash';
    const HASH_IS_EXIST = 'hash_is_exist';
    const AUDIO_CONTENT = 'audio_content';
    const AUDIO_MESSAGE_LENGTH = 500;
    const RAW_BODY = 'rawBody';
    const TO_REMOVE = 'to_remove';
    const FILE_EXISTS = 'file_exists';

    const DYNAMICUS_KEY = 'dynamicus';

    const GOOGLE_API_NAME = 'google_api_name';
}
