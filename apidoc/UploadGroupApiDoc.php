<?php

/**
 * @api {post} /user/avatar upload avatar user
 * @apiVersion 0.1.0
 * @apiName UploadUserAvatar
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file Media File image avatar Multipart form
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "user_id": 2,
 *              "image_path": "2-2020-03-26_06-50-19-819840.png",
 *              "image_url": "http://192.168.64.2/storage/users/2-2020-03-26_06-50-19-819840.png"
 *          }
 *     }
 */

 /**
 * @api {post} /albums/files  Upload media files for album
 * @apiVersion 0.1.0
 * @apiName UploadMediaFilesAlbum
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} files[] Media File List Multipart form
 * @apiParam {Integer} media_type Media type 1: Image 2: Video
 *
 * @apiSuccess {Object} files
 * @apiSuccess {String} files.path link image or video
 * @apiSuccess {String} files.thumbnail_path thumbnail path for video file
 * @apiSuccess {String} files.created_user user create
 * @apiSuccess {String} files.type media type 1: Image 2: Video
 * @apiSuccess {Integer} files.status 0: upload fail 1: upload success
 * @apiSuccess {Integer} files.size total size in storage (bytes)
 *
 * @apiSuccessExample {json} Success-Example:
 * {
 *      {
 *   "code": 200,
 *   "data": {
 *       "files": [
 *           {
 *               "path": "2020-03-26_08-37-54-976695.png",
 *               "url": "http://192.168.64.2/storage/images/2020-03-26_08-37-54-976695.png",
 *               "thumbnail_path": "",
 *               "thumbnail_url": "",
 *               "created_user": 2,
 *               "type": 1,
 *               "status": 1,
 *               "size": 1000
 *           },
 *           {
 *               "path": "2020-03-26_08-37-54-985149.png",
 *               "url": "http://192.168.64.2/storage/images/2020-03-26_08-37-54-985149.png",
 *               "thumbnail_path": "",
 *               "thumbnail_path": "",
 *               "created_user": 2,
 *               "type": 1,
 *               "status": 1,
 *               "size": 1000
 *           }
 *       ]
 *   }
 * }
 */

 /**
 * @api {post} /albums/avatar upload avatar album
 * @apiVersion 0.1.0
 * @apiName UploadAlbumAvatar
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file Media File image avatar Multipart form
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "user_id": 2,
 *              "image_path": "2-2020-03-26_06-50-19-819840.png",
 *              "image_url": "http://192.168.64.2/storage/users/2-2020-03-26_06-50-19-819840.png"
 *          }
 *     }
 */

 /**
 * @api {post} /albums/:albumId/locations/:albumLocationId/files  insert media files for location
 * @apiVersion 0.1.0
 * @apiName InsertMediaFilesToLocation
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Object[]} medias Medias Image or video List
 * @apiParam {File} medias.file Medias Image or video file
 * @apiParam {String} [medias.description] Media description
 * @apiParam {string} [medias.created_time] Media created time
 * @apiParam {Object[]} [medias.information] Media Information
 * @apiParam {Number} medias.information.media_property_id Media property id
 * @apiParam {string} [medias.information.value] Media property value
 * @apiParam {Integer} media_type Multipart Media type 1: Image 2: Video
 *
 * @apiSuccessExample {json} Success-Example:
 *        {
 *            "code": 200,
 *            "data": {
 *                "medias": [
 *                    {
 *                        "id": 35,
 *                        "path": "1-2020-09-09_19-39-51-492332.jpeg",
 *                        "url": "http://localhost/storage/images/1-2020-09-09_19-39-51-492332.jpeg",
 *                        "image_after_path": null,
 *                        "image_after_url": null,
 *                        "thumbnail_path": null,
 *                        "thumbnail_url": null,
 *                        "album_location_id": 30,
 *                        "description": "",
  *                       "created_time": "09/09/2020",
 *                        "information": [
 *                            {
 *                                "id": 20,
 *                                "media_property_id": 1,
 *                                "title": "Ngày Chụp Ảnh",
 *                                "display": 1,
 *                                "highlight": 1,
 *                                "value": "2020-09-10"
 *                            }
 *                        ],
 *                        "comments": [
 *                            {
 *                                "id": 17,
 *                                "creator": {
 *                                    "shared_album_id": 3,
 *                                    "full_name": "Hoang Tung Lam",
 *                                    "email": "hoanglam995@gmail.com"
 *                                },
 *                                "creator_type": 2,
 *                                "content": "comment content",
 *                                "create_at": "2020-08-14T11:34:35.000000Z",
 *                                "update_at": "2020-08-14T11:34:35.000000Z"
 *                            },
 *                            {
 *                                "id": 21,
 *                                "creator": {
 *                                    "id": 1,
 *                                    "full_name": "Hoàng Tùng Lâm",
 *                                    "email": "hoanglam995@gmail.com",
 *                                    "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                },
 *                                "creator_type": 1,
 *                                "content": "comment content",
 *                                "create_at": "2020-08-17T04:35:11.000000Z",
 *                                "update_at": "2020-08-17T04:35:11.000000Z"
 *                            }
 *                        ],
 *                        "number_comment": 2,
 *                        "type": 1,
 *                        "size": 0.00012
 *                    }
 *                ]
 *            }
 *        }
 */

/**
 * @api {post} /albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId  Update File Album Location Media
 * @apiVersion 0.1.0
 * @apiName UpdateFileAlbumLocationMedia
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 * @apiHeader {Number} Force-Update Force update: 1 - not check modify, 0 - check modify
 *
 * @apiParam {File} [file] Medias Image or video file
 * @apiParam {String} [description] Media description
 * @apiParam {String} latest_updated_at Latest updated at Album location media
 * @apiParam {string} [created_time] Media created time
 * @apiParam {Object[]} [information] Media Information
 * @apiParam {Number} information.media_property_id Media property id
 * @apiParam {string} [information.value] Media property value
 * @apiParam {Integer} [media_type] Multipart Media type 1: Image 2: Video. Require if has file
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "media": {
 *                   "id": 35,
 *                   "path": "1-2020-09-09_19-55-40-243407.jpeg",
 *                   "url": "http://localhost/storage/images/1-2020-09-09_19-55-40-243407.jpeg",
 *                   "image_after_path": null,
 *                   "image_after_url": null,
 *                   "thumbnail_path": null,
 *                   "thumbnail_url": null,
 *                   "album_location_id": 30,
 *                   "description": "",
 *                   "created_time": "09/09/2020",
 *                   "information": [
 *                       {
 *                           "id": 20,
 *                           "media_property_id": 1,
 *                           "title": "Ngày Chụp Ảnh",
 *                           "display": 1,
 *                           "highlight": 1,
 *                           "value": "2020-09-10"
 *                       }
 *                   ],
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
 *                   "type": 1,
 *                   "size": 0.00012
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
 * @api {post} /albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId/image-after  Update after image of Album Location Media
 * @apiVersion 0.1.0
 * @apiName UpdateAfterImageAlbumLocationMedia
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file Image file
 * @apiParam {Number} action_type Action type: 1 - upload new image, 2 - update image
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "media": {
 *                   "id": 35,
 *                   "path": "1-2020-09-09_19-55-40-243407.jpeg",
 *                   "url": "http://localhost/storage/images/1-2020-09-09_19-55-40-243407.jpeg",
 *                   "image_after_path": null,
 *                   "image_after_url": null,
 *                   "thumbnail_path": null,
 *                   "thumbnail_url": null,
 *                   "album_location_id": 30,
 *                   "description": "",
 *                   "created_time": "09/09/2020",
 *                   "information": [
 *                       {
 *                           "id": 20,
 *                           "media_property_id": 1,
 *                           "title": "Ngày Chụp Ảnh",
 *                           "display": 1,
 *                           "highlight": 1,
 *                           "value": "2020-09-10"
 *                       }
 *                   ],
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
 *                   "type": 1,
 *                   "size": 0.00012
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
 * @api {post} /albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId/image-before  Update before image of Album Location Media
 * @apiVersion 0.1.0
 * @apiName UpdateBeforeImageAlbumLocationMedia
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file Image file
 * @apiParam {Number} action_type Action type: 1 - upload new image, 2 - update image
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "media": {
 *                   "id": 35,
 *                   "path": "1-2020-09-09_19-55-40-243407.jpeg",
 *                   "url": "http://localhost/storage/images/1-2020-09-09_19-55-40-243407.jpeg",
 *                   "image_after_path": null,
 *                   "image_after_url": null,
 *                   "thumbnail_path": null,
 *                   "thumbnail_url": null,
 *                   "album_location_id": 30,
 *                   "description": "",
 *                   "created_time": "09/09/2020",
 *                   "information": [
 *                       {
 *                           "id": 20,
 *                           "media_property_id": 1,
 *                           "title": "Ngày Chụp Ảnh",
 *                           "display": 1,
 *                           "highlight": 1,
 *                           "value": "2020-09-10"
 *                       }
 *                   ],
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
 *                   "type": 1,
 *                   "size": 0.00012
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
 * @api {post} /admin/avatar upload avatar admin
 * @apiVersion 0.1.0
 * @apiName UploadAdminAvatar
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file Media File image avatar Multipart form
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "admin_id": 2,
 *              "image_path": "2-2020-03-26_06-50-19-819840.png",
 *              "image_url": "http://192.168.64.2/storage/users/2-2020-03-26_06-50-19-819840.png"
 *          }
 *     }
 */

 /**
 * @api {post} /albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId/swap  swap before and after image
 * @apiVersion 0.1.0
 * @apiName SwapBeforeAfterImageAlbumLocationMedia
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "media": {
 *                   "id": 35,
 *                   "path": "1-2020-09-09_19-55-40-243407.jpeg",
 *                   "url": "http://localhost/storage/images/1-2020-09-09_19-55-40-243407.jpeg",
 *                   "image_after_path": "1-2020-09-09_19-55-40-2434077.jpeg",
 *                   "image_after_url": "http://localhost/storage/images/1-2020-09-09_19-55-40-2434077.jpeg",
 *                   "thumbnail_path": null,
 *                   "thumbnail_url": null,
 *                   "album_location_id": 30,
 *                   "description": "",
 *                   "created_time": "09/09/2020",
 *                   "information": [
 *                       {
 *                           "id": 20,
 *                           "media_property_id": 1,
 *                           "title": "Ngày Chụp Ảnh",
 *                           "display": 1,
 *                           "highlight": 1,
 *                           "value": "2020-09-10"
 *                       }
 *                   ],
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
 *                   "type": 1,
 *                   "size": 0.00012
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
 * @api {delete} /albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId/image-after  Delete after image of Album Location Media
 * @apiVersion 0.1.0
 * @apiName DeleteAfterImageAlbumLocationMedia
 * @apiGroup Upload
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {}
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
