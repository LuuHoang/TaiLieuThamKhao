<?php
/**
 * @api {get} /app/companies/:companyId/album-settings Get Album Setting
 * @apiVersion 0.1.0
 * @apiName GetAlbumSetting
 * @apiGroup Album Setting For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.album_settings
 *
 * @apiSuccess {Object[]} data.album_settings.album_properties
 * @apiSuccess {Number} data.album_settings.album_properties.id Album property ID
 * @apiSuccess {String} data.album_settings.album_properties.title Album property title
 * @apiSuccess {String} data.album_settings.album_properties.description Album property description
 * @apiSuccess {Number} data.album_settings.album_properties.type Album property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.album_settings.album_properties.require Album property require
 * @apiSuccess {Boolean} data.album_settings.album_properties.display Display property in item list album
 * @apiSuccess {Boolean} data.album_settings.album_properties.highlight Highlight property in item list album
 *
 * @apiSuccess {Object[]} data.album_settings.album_types
 * @apiSuccess {Number} data.album_settings.album_types.id Album type ID
 * @apiSuccess {String} data.album_settings.album_types.title Album type title
 *
 * @apiSuccess {Object[]} data.album_settings.location_properties
 * @apiSuccess {Number} data.album_settings.location_properties.id Location property ID
 * @apiSuccess {String} data.album_settings.location_properties.title Location property title
 * @apiSuccess {Number} data.album_settings.location_properties.type Location property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.album_settings.location_properties.display Display property in item list location
 * @apiSuccess {Boolean} data.album_settings.location_properties.highlight Highlight property in item list location
 *
 * @apiSuccess {Object[]} data.album_settings.location_types
 * @apiSuccess {Number} data.album_settings.location_types.id Location type ID
 * @apiSuccess {String} data.album_settings.location_types.title Location type title
 *
 * @apiSuccess {Object[]} data.album_settings.media_properties
 * @apiSuccess {Number} data.album_settings.media_properties.id Media property ID
 * @apiSuccess {String} data.album_settings.media_properties.title Media property title
 * @apiSuccess {Number} data.album_settings.media_properties.type Media property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.album_settings.media_properties.display Display property in item list media
 * @apiSuccess {Boolean} data.album_settings.media_properties.highlight Highlight property in item list media
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_settings": {
 *                   "album_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0
 *                       }
 *                   ],
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）"
 *                       }
 *                   ],
 *                   "location_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "Diện Tích",
 *                           "type": 1,
 *                           "display": 0,
 *                           "highlight": 1
 *                       }
 *                   ],
 *                   "location_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "リビング　Living room"
 *                       }
 *                   ],
 *                   "media_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "Ngày Chụp Ảnh",
 *                           "type": 1,
 *                           "display": 1,
 *                           "highlight": 1
 *                       }
 *                   ]
 *               }
 *           }
 *       }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {get} /app/companies/:companyId/location-types Get Location Type
 * @apiVersion 0.1.0
 * @apiName GetLocationType
 * @apiGroup Album Setting For App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title location type
 *
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "location_types": [
 *                   {
 *                       "id": 1,
 *                       "title": "Location Type 1"
 *                   }
 *              ]
 *          }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {get} /app/companies/:companyId/location-properties Get Location Properties
 * @apiVersion 0.1.0
 * @apiName GetLocationProperties
 * @apiGroup Album Setting For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "location_properties": [
 *                   {
 *                       "id": 1,
 *                       "title": "Diện Tích",
 *                       "type": 1,
 *                       "display": 0,
 *                       "highlight": 1
 *                   }
 *               ]
 *          }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {get} /app/companies/:companyId/media-properties Get media Properties
 * @apiVersion 0.1.0
 * @apiName GetMediaProperties
 * @apiGroup Album Setting For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "media_properties": [
 *                   {
 *                       "id": 1,
 *                       "title": "Ngày Chụp Ảnh",
 *                       "type": 1,
 *                       "display": 1,
 *                       "highlight": 1
 *                   }
 *               ]
 *          }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */
