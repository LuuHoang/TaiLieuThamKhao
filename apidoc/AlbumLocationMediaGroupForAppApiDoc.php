<?php

/**
 * @api {delete} /app/albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId Delete Album Location Media
 * @apiVersion 0.1.0
 * @apiName DeleteAlbumLocationMedia
 * @apiGroup Album Location Media For App
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

/**
 * @api {get} /app/albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId Get Album Location Media Detail
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationMediaDetail
 * @apiGroup Album Location Media For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "media": {
 *                 "id": 1,
 *                 "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                 "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                 "image_after_path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                 "image_after_url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                 "thumbnail_path": null,
 *                 "thumbnail_url": null,
 *                 "album_location_id": 30,
 *                 "description": "description",
 *                 "created_time": "09/09/2020",
 *                 "information": [
 *                     {
 *                        "id": 19,
 *                        "media_property_id": 1,
 *                        "title": "Ngày Chụp Ảnh",
 *                        "type": 1
 *                        "display": 1,
 *                        "highlight": 1,
 *                        "value": "09/09/2020"
 *                     }
 *                 ],
 *                 "comments": [
 *                     {
 *                         "id": 17,
 *                         "creator": {
 *                             "shared_album_id": 3,
 *                             "full_name": "Hoang Tung Lam",
 *                             "email": "hoanglam995@gmail.com"
 *                         },
 *                         "creator_type": 2,
 *                         "content": "comment content",
 *                         "create_at": "2020-08-14T11:34:35.000000Z",
 *                         "update_at": "2020-08-14T11:34:35.000000Z"
 *                     },
 *                     {
 *                         "id": 21,
 *                         "creator": {
 *                             "id": 1,
 *                             "full_name": "Hoàng Tùng Lâm",
 *                             "email": "hoanglam995@gmail.com",
 *                             "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                         },
 *                         "creator_type": 1,
 *                         "content": "comment content",
 *                         "create_at": "2020-08-17T04:35:11.000000Z",
 *                         "update_at": "2020-08-17T04:35:11.000000Z"
 *                     }
 *                 ],
 *                 "number_comment": 2,
 *                 "type": 1,
 *                 "size": 0.1
 *             }
 *         }
 *     }
 *
 *
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
 * @api {put} /app/albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId/change Change location of album location media
 * @apiVersion 0.1.0
 * @apiName ChangeLocationOfAlbumLocationMedia
 * @apiGroup Album Location Media For App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} location_title Location title
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album": {
 *                   "id": 41,
 *                   "user_id": 1,
 *                   "user_created_data" : {
 *                           "id": 1,
 *                           "full_name": "Hoang Lam",
 *                           "email": "hoanglam995@gmail.com",
 *                           "avatar_url": "http://localhost/storage/users/1-2020-09-15_09-14-10-098666.jpeg"
 *                   },
 *                   "image_path": "image_path.jpg",
 *                   "image_url": "http://localhost/storage/images/image_path.jpg",
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）",
 *                           "checked": 1
 *                       },
 *                       {
 *                           "id": 2,
 *                           "title": "マンション（Appartment）",
 *                           "checked": 0
 *                       }
 *                   ],
 *                   "album_locations": [
 *                       {
 *                           "id": 30,
 *                           "title": "Bedroom",
 *                           "description": "Gara oto description",
 *                           "information": [
 *                               {
 *                                  "id": 12,
 *                                  "location_property_id": 1,
 *                                  "title": "Diện Tích",
 *                                  "type": 1,
 *                                  "display": 0,
 *                                  "highlight": 1,
 *                                  "value": "45m2"
 *                               }
 *                           ],
 *                           "location_image": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                           "comments": [
 *                               {
 *                                   "id": 17,
 *                                   "creator": {
 *                                       "shared_album_id": 3,
 *                                       "full_name": "Hoang Tung Lam",
 *                                       "email": "hoanglam995@gmail.com"
 *                                   },
 *                                   "creator_type": 2,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-14T11:34:35.000000Z",
 *                                   "update_at": "2020-08-14T11:34:35.000000Z"
 *                               },
 *                               {
 *                                   "id": 21,
 *                                   "creator": {
 *                                       "id": 1,
 *                                       "full_name": "Hoàng Tùng Lâm",
 *                                       "email": "hoanglam995@gmail.com",
 *                                       "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                   },
 *                                   "creator_type": 1,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-17T04:35:11.000000Z",
 *                                   "update_at": "2020-08-17T04:35:11.000000Z"
 *                               }
 *                           ],
 *                           "number_comment": 2,
 *                           "created_at": "2020-09-09T06:26:57.000000Z",
 *                           "ts_created_at": 1599632817,
 *                           "medias": [
 *                               {
 *                                   "id": 1,
 *                                   "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "image_after_path": null,
 *                                   "image_after_url": null,
 *                                   "thumbnail_path": null,
 *                                   "thumbnail_url": null,
 *                                   "album_location_id": 30,
 *                                   "description": "description",
 *                                   "created_time": "09/09/2020",
 *                                   "information": [
 *                                       {
 *                                          "id": 19,
 *                                          "media_property_id": 1,
 *                                          "title": "Ngày Chụp Ảnh",
 *                                          "type": 1,
 *                                          "display": 1,
 *                                          "highlight": 1,
 *                                          "value": "09/09/2020"
 *                                       }
 *                                   ],
 *                                   "comments": [
 *                                       {
 *                                           "id": 17,
 *                                           "creator": {
 *                                               "shared_album_id": 3,
 *                                               "full_name": "Hoang Tung Lam",
 *                                               "email": "hoanglam995@gmail.com"
 *                                           },
 *                                           "creator_type": 2,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-14T11:34:35.000000Z",
 *                                           "update_at": "2020-08-14T11:34:35.000000Z"
 *                                       },
 *                                       {
 *                                           "id": 21,
 *                                           "creator": {
 *                                               "id": 1,
 *                                               "full_name": "Hoàng Tùng Lâm",
 *                                               "email": "hoanglam995@gmail.com",
 *                                               "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                           },
 *                                           "creator_type": 1,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-17T04:35:11.000000Z",
 *                                           "update_at": "2020-08-17T04:35:11.000000Z"
 *                                       }
 *                                   ],
 *                                   "number_comment": 2,
 *                                   "type": 1,
 *                                   "size": 0.1
 *                               }
 *                           ]
 *                       }
 *                   ],
 *                   "album_informations": [
 *                       {
 *                           "id": 42,
 *                           "album_property_id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "House ID"
 *                       },
 *                       {
 *                           "id": "",
 *                           "album_property_id": 2,
 *                           "title": "所有者（Owner）",
 *                           "type": 1,
 *                           "require": 0,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": ""
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
