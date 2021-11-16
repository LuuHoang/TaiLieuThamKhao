<?php

/**
 * @api {post} /setting Setting App
 * @apiVersion 0.1.0
 * @apiName SettingApp
 * @apiGroup User Setting
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} image_size image size upload limit
 * @apiParam {String} language JA: Japanese, EN: English, VI: Vietnamese
 * @apiParam {Boolean} voice setting voice comment
 * @apiParam {Boolean} [comment_display] setting comment display
 *
 * @apiParamExample {json} param-Example:
 *  {
 *      "image_size": 2,
 *      "language": "JA|EN|VI",
 *      "voice": true|false,
 *      "comment_display": true|false
 * }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "user_setting": {
 *                  "image_size": 2,
 *                  "language": JA,
 *                  "voice": true,
 *                  "comment_display": true,
 *              }
 *          }
 *     }
 */

 /**
 * @api {get} /setting  Get User Setting
 * @apiVersion 0.1.0
 * @apiName GetUserSetting
 * @apiGroup User Setting
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess image_size
 * @apiSuccess language
 * @apiSuccess voice
 * @apiSuccess comment_display
 *
 * @apiSuccessExample {json} Success-Example:
 * {
 *      "user_setting": {
 *          "image_size": 2,
 *          "language": JA,
 *          "voice": true,
  *         "comment_display": true
 *      }
 * }
 */
