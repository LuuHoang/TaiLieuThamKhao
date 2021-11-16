<?php

/**
 * @apiDefine HeaderToken
 *
 * @apiHeader {String} Authorization Bearer <code>token</code>
 */

/**
 * @apiDefine SystemError
 *
 * @apiError system_error System has error
 * @apiErrorExample {json} Error-Example:
 *      HTTP1/1: 500 Internal Server Error
 *      {
 *          "code": 500,
 *          "messages": [
 *              "system_error"
 *          ]
 *      }
 */
