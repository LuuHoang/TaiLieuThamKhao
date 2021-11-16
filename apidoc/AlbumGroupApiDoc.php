<?php

/**
 * @api {get} /albums/:albumId/locations/:locationId Get Album Location Detail
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationDetail
 * @apiGroup Albums
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "location": {
 *                   "id": 30,
 *                   "title": "Bedroom",
 *                   "description": "Gara oto description",
 *                   "information": [
 *                       {
 *                          "id": 12,
 *                          "location_property_id": 1,
 *                          "title": "Diện Tích",
 *                          "type": 1,
 *                          "display": 0,
 *                          "highlight": 1,
 *                          "value": "45m2"
 *                       }
 *                   ],
 *                   "location_image": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                   "comments": [
 *                       {
 *                           "id": 17,
 *                           "creator": {
 *                               "shared_album_id": 3,
 *                               "full_name": "Hoang Tung Lam",
 *                               "email": "hoanglam995@gmail.com"
 *                           },
 *                           "creator_type": 2,
 *                           "content": "comment content",
 *                           "create_at": "2020-08-14T11:34:35.000000Z",
 *                           "update_at": "2020-08-14T11:34:35.000000Z"
 *                       },
 *                       {
 *                           "id": 21,
 *                           "creator": {
 *                               "id": 1,
 *                               "full_name": "Hoàng Tùng Lâm",
 *                               "email": "hoanglam995@gmail.com",
 *                               "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                           },
 *                           "creator_type": 1,
 *                           "content": "comment content",
 *                           "create_at": "2020-08-17T04:35:11.000000Z",
 *                           "update_at": "2020-08-17T04:35:11.000000Z"
 *                       }
 *                   ],
 *                   "number_comment": 2,
 *                   "created_at": "2020-09-09T06:26:57.000000Z",
 *                   "ts_created_at": 1599632817,
 *                   "medias": [
 *                       {
 *                           "id": 1,
 *                           "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                           "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                           "image_after_path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                           "image_after_url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                           "thumbnail_path": null,
 *                           "thumbnail_url": null,
 *                           "album_location_id": 30,
 *                           "description": "description",
 *                           "created_time": "09/09/2020",
 *                           "information": [
 *                               {
 *                                  "id": 19,
 *                                  "media_property_id": 1,
 *                                  "title": "Ngày Chụp Ảnh",
 *                                  "type": 1
 *                                  "display": 1,
 *                                  "highlight": 1,
 *                                  "value": "09/09/2020"
 *                               }
 *                           ],
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
 *                           "type": 1,
 *                           "size": 0.1
 *                       }
 *                   ]
 *               }
 *           }
 *       }
 *
 */

/**
 * @api {delete} /albums/:albumId Delete Album
 * @apiVersion 0.1.0
 * @apiName DeleteAlbum
 * @apiGroup Albums
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
 * @api {post} /albums/files/prepare-upload Prepare Upload Media
 * @apiVersion 0.1.0
 * @apiName PrepareUploadMedia
 * @apiGroup Albums
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} size
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
 *       "code": 422,
 *       "message": "Not allowed to upload file"
 *     }
 */


/**
 * @api {post} /albums/files/prepare-upload Prepare Upload Media
 * @apiVersion 0.1.0
 * @apiName PrepareUploadMedia
 * @apiGroup Albums
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} size
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
 *       "code": 422,
 *       "message": "Not allowed to upload file"
 *     }
 */

/**
 * @api {post} /albums/files/prepare-upload Prepare Upload Media
 * @apiVersion 0.1.0
 * @apiName PrepareUploadMedia
 * @apiGroup Albums
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} size
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
 *         "code": 422,
 *         "message": "Not allowed to upload file"
 *     }
 */
