<?php

/**
 * @api {post} /app/albums/:albumId/locations Create Album Locations
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocations
 * @apiGroup Album Location For App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Object[]} locations Locations
 * @apiParam {String} locations.title Title location
 * @apiParam {String} [locations.description] Description location
 * @apiParam {Object[]} [locations.information] Location Information
 * @apiParam {Number} locations.information.location_property_id Location property id
 * @apiParam {string} [locations.information.value] Location property value
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *            "locations": [
 *                {
 *                    "title": "Gara",
 *                    "description": "Gara oto description",
 *                    "information": [
 *                        {
 *                            "location_property_id": 1,
 *                            "value": "30m2"
 *                        }
 *                    ]
 *                }
 *            ]
 *       }
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "locations": [
 *                    {
 *                        "id": 37,
 *                        "title": "Gara",
 *                        "description": "Gara oto description",
 *                        "information": [
 *                            {
 *                                "id": 16,
 *                                "location_property_id": 1,
 *                                "title": "Diện Tích",
 *                                "type": 1,
 *                                "display": 0,
 *                                "highlight": 1,
 *                                "value": "30m2"
 *                            }
 *                        ],
 *                        "location_image": "",
 *                        "comments": [],
 *                        "number_comment": 0,
 *                        "created_at": "2020-09-09T10:41:19.000000Z",
 *                        "ts_created_at": 1599648079,
 *                        "medias": []
 *                    }
 *                ]
 *           }
 *       }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *         "code": 400,
 *         "message": "System Error!"
 *     }
 */

/**
 * @api {put} /app/albums/:albumId/locations/:locationId Update Album Location
 * @apiVersion 0.1.0
 * @apiName UpdateAlbumLocation
 * @apiGroup Album Location For App
 *
 * @apiUse HeaderToken
 * @apiHeader {Number} Force-Update Force update: 1 - not check modify, 0 - check modify
 *
 * @apiParam {String} [description] Description location
 * @apiParam {String} latest_updated_at Latest updated at Album location
 * @apiParam {Object[]} [information] Location Information
 * @apiParam {Number} information.location_property_id Location property id
 * @apiParam {string} [information.value] Location property value
 * @apiParam {Object[]} [medias] Medias
 * @apiParam {Number} medias.id Media id
 * @apiParam {string} [medias.description] Media description
 * @apiParam {string} [medias.created_time] Media created time
 * @apiParam {Object[]} [medias.information] Media Information
 * @apiParam {Number} medias.information.media_property_id Media property id
 * @apiParam {string} [medias.information.value] Media property value
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *           "description": "Gara oto description",
 *           "information": [
 *               {
 *                   "location_property_id": 1,
 *                   "value": "45m2"
 *               }
 *           ],
 *           "medias": [
 *               {
 *                   "id": 1,
 *                   "description": "ảnh chup toàn cảnh",
 *                   "created_time": "09/09/2020",
 *                   "information": [
 *                       {
 *                          "media_property_id": 1,
 *                          "value": "09/09/2020"
 *                       }
 *                   ]
 *               }
 *           ]
 *       }
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "location": {
 *                   "id": 30,
 *                   "title": "Bedroom",
 *                   "description": "Gara oto description",
 *                   "information": [
 *                       {
 *                           "id": 12,
 *                           "location_property_id": 1,
 *                           "title": "Diện Tích",
 *                           "type" : 1,
 *                           "display": 0,
 *                           "highlight": 1,
 *                           "value": "45m2"
 *                       }
 *                   ],
 *                   "location_image": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                   "comments": [],
 *                   "number_comment": 0,
 *                   "created_at": "2020-09-09T06:26:57.000000Z",
 *                   "ts_created_at": 1599632817,
 *                   "medias": [
 *                       {
 *                           "id": 1,
 *                           "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                           "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                           "image_after_path": null,
 *                           "image_after_url": null,
 *                           "thumbnail_path": null,
 *                           "thumbnail_url": null,
 *                           "album_location_id": 30,
 *                           "description": "ảnh chup toàn cảnh",
 *                           "created_time": "09/09/2020",
 *                           "information": [
 *                               {
 *                               "id": 19,
 *                               "media_property_id": 1,
 *                               "title": "Ngày Chụp Ảnh",
 *                               "type": 1,
 *                               "display": 1,
 *                               "highlight": 1,
 *                               "value": "09/09/2020"
 *                               }
 *                           ],
 *                           "comments": [],
 *                           "number_comment": 0,
 *                           "type": 1,
 *                           "size": 0.5
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
 *         "code": 400,
 *         "message": "System Error!"
 *     }
 */

/**
 * @api {delete} /app/albums/:albumId/locations/:albumLocationId Delete Album Location
 * @apiVersion 0.1.0
 * @apiName DeleteAlbumLocation
 * @apiGroup Album Location For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {}
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
