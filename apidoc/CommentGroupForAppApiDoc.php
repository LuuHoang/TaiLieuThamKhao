<?php

/**
 * @api {get} /app/albums/:albumId/locations/:locationId/comments Get album location comment
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} after_time Get location comment after time. Ex: ?after_time=2020-10-06T02:51:15.000000Z
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object[]} data.data_list
 *
 * @apiSuccess {Number} data.data_list.id Album location comment id
 * @apiSuccess {Object} data.data_list.creator Comment creator
 * @apiSuccess {Number} data.data_list.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.data_list.content Comment content
 * @apiSuccess {String} data.data_list.create_at Comment created time
 * @apiSuccess {String} data.data_list.update_at Comment updated time
 *
 * @apiSuccess {Number} data.total Total comment
 * @apiSuccess {Number} data.current_page Current page comment
 * @apiSuccess {Number} data.limit Limit comment
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 21,
 *                       "creator": {
 *                           "id": 1,
 *                           "full_name": "Hoàng Tùng Lâm",
 *                           "email": "hoanglam995@gmail.com",
 *                           "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                       },
 *                       "creator_type": 1,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-17T04:35:11.000000Z",
 *                       "update_at": "2020-08-17T04:35:11.000000Z"
 *                   },
 *                   {
 *                       "id": 22,
 *                       "creator": {
 *                           "shared_album_id": 3,
 *                           "full_name": "Hoang Tung Lam",
 *                           "email": "hoanglam995@gmail.com"
 *                       },
 *                       "creator_type": 2,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-19T03:31:30.000000Z",
 *                       "update_at": "2020-08-19T03:31:30.000000Z"
 *                   }
 *               ],
 *               "total": 9,
 *               "current_page": 1,
 *               "limit": 2
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
 * @api {get} /app/albums/:albumId/locations/:locationId/new-comments Get new album location comment
 * @apiVersion 0.1.0
 * @apiName GetNewAlbumLocationComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} after_time Get location comment after time. Ex: ?after_time=2020-10-06T02:51:15.000000Z
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object[]} data.data_list
 *
 * @apiSuccess {Number} data.data_list.id Album location comment id
 * @apiSuccess {Object} data.data_list.creator Comment creator
 * @apiSuccess {Number} data.data_list.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.data_list.content Comment content
 * @apiSuccess {String} data.data_list.create_at Comment created time
 * @apiSuccess {String} data.data_list.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 21,
 *                       "creator": {
 *                           "id": 1,
 *                           "full_name": "Hoàng Tùng Lâm",
 *                           "email": "hoanglam995@gmail.com",
 *                           "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                       },
 *                       "creator_type": 1,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-17T04:35:11.000000Z",
 *                       "update_at": "2020-08-17T04:35:11.000000Z"
 *                   },
 *                   {
 *                       "id": 22,
 *                       "creator": {
 *                           "shared_album_id": 3,
 *                           "full_name": "Hoang Tung Lam",
 *                           "email": "hoanglam995@gmail.com"
 *                       },
 *                       "creator_type": 2,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-19T03:31:30.000000Z",
 *                       "update_at": "2020-08-19T03:31:30.000000Z"
 *                   }
 *               ]
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
 * @api {post} /app/albums/:albumId/locations/:locationId/comments Create album location comment
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocationComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} content Album location comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.album_location_comment
 *
 * @apiSuccess {Number} data.album_location_comment.id Album location comment id
 * @apiSuccess {Object} data.album_location_comment.creator Comment creator
 * @apiSuccess {Number} data.album_location_comment.creator.id User create id
 * @apiSuccess {String} data.album_location_comment.creator.full_name User create full_name
 * @apiSuccess {String} data.album_location_comment.creator.email User create email
 * @apiSuccess {String} data.album_location_comment.creator.avatar_url User create avatar image url
 * @apiSuccess {Number} data.album_location_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.album_location_comment.content Comment content
 * @apiSuccess {String} data.album_location_comment.create_at comment created time
 * @apiSuccess {String} data.album_location_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "album_location_comment": {
 *               "id": 21,
 *               "creator": {
 *                   "id": 1,
 *                   "full_name": "Hoàng Tùng Lâm",
 *                   "email": "hoanglam995@gmail.com",
 *                   "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *               },
 *               "creator_type": 1,
 *               "content": "comment content",
 *               "create_at": "2020-08-17T04:35:11.000000Z",
 *               "update_at": "2020-08-17T04:35:11.000000Z"
 *           }
 *       }
 *   }
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
 * @api {put} /app/albums/locations/:locationId/comments/:commentId Edit Album Location comment
 * @apiVersion 0.1.0
 * @apiName EditAlbumLocationComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} content Album location comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.album_location_comment
 *
 * @apiSuccess {Number} data.album_location_comment.id Album location comment id
 * @apiSuccess {Object} data.album_location_comment.creator Comment creator
 * @apiSuccess {Number} data.album_location_comment.creator.id User create id
 * @apiSuccess {String} data.album_location_comment.creator.full_name User create full_name
 * @apiSuccess {String} data.album_location_comment.creator.email User create email
 * @apiSuccess {String} data.album_location_comment.creator.avatar_url User create avatar image url
 * @apiSuccess {Number} data.album_location_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.album_location_comment.content Comment content
 * @apiSuccess {String} data.album_location_comment.create_at comment created time
 * @apiSuccess {String} data.album_location_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "album_location_comment": {
 *               "id": 21,
 *               "creator": {
 *                   "id": 1,
 *                   "full_name": "Hoàng Tùng Lâm",
 *                   "email": "hoanglam995@gmail.com",
 *                   "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *               },
 *               "creator_type": 1,
 *               "content": "comment content",
 *               "create_at": "2020-08-17T04:35:11.000000Z",
 *               "update_at": "2020-08-17T05:35:11.000000Z"
 *           }
 *       }
 *   }
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
 * @api {get} /app/albums/locations/:locationId/medias/:mediaId/comments Get album location media comment
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationMediaComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} after_time Get location media comment after time. Ex: ?after_time=2020-10-06T02:51:15.000000Z
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object[]} data.data_list
 *
 * @apiSuccess {Number} data.data_list.id Album location comment id
 * @apiSuccess {Object} data.data_list.creator Comment creator
 * @apiSuccess {Number} data.data_list.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.data_list.content Comment content
 * @apiSuccess {String} data.data_list.create_at Comment created time
 * @apiSuccess {String} data.data_list.update_at Comment updated time
 *
 * @apiSuccess {Number} data.total Total comment
 * @apiSuccess {Number} data.current_page Current page comment
 * @apiSuccess {Number} data.limit Limit comment
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 21,
 *                       "creator": {
 *                           "id": 1,
 *                           "full_name": "Hoàng Tùng Lâm",
 *                           "email": "hoanglam995@gmail.com",
 *                           "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                       },
 *                       "creator_type": 1,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-17T04:35:11.000000Z",
 *                       "update_at": "2020-08-17T04:35:11.000000Z"
 *                   },
 *                   {
 *                       "id": 22,
 *                       "creator": {
 *                           "shared_album_id": 3,
 *                           "full_name": "Hoang Tung Lam",
 *                           "email": "hoanglam995@gmail.com"
 *                       },
 *                       "creator_type": 2,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-19T03:31:30.000000Z",
 *                       "update_at": "2020-08-19T03:31:30.000000Z"
 *                   }
 *               ],
 *               "total": 9,
 *               "current_page": 1,
 *               "limit": 2
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
 * @api {get} /app/albums/locations/:locationId/medias/:mediaId/new-comments Get new album location media comment
 * @apiVersion 0.1.0
 * @apiName GetNewAlbumLocationMediaComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} after_time Get location media comment after time. Ex: ?after_time=2020-10-06T02:51:15.000000Z
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object[]} data.data_list
 *
 * @apiSuccess {Number} data.data_list.id Album location comment id
 * @apiSuccess {Object} data.data_list.creator Comment creator
 * @apiSuccess {Number} data.data_list.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.data_list.content Comment content
 * @apiSuccess {String} data.data_list.create_at Comment created time
 * @apiSuccess {String} data.data_list.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 21,
 *                       "creator": {
 *                           "id": 1,
 *                           "full_name": "Hoàng Tùng Lâm",
 *                           "email": "hoanglam995@gmail.com",
 *                           "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                       },
 *                       "creator_type": 1,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-17T04:35:11.000000Z",
 *                       "update_at": "2020-08-17T04:35:11.000000Z"
 *                   },
 *                   {
 *                       "id": 22,
 *                       "creator": {
 *                           "shared_album_id": 3,
 *                           "full_name": "Hoang Tung Lam",
 *                           "email": "hoanglam995@gmail.com"
 *                       },
 *                       "creator_type": 2,
 *                       "content": "comment content",
 *                       "create_at": "2020-08-19T03:31:30.000000Z",
 *                       "update_at": "2020-08-19T03:31:30.000000Z"
 *                   }
 *               ]
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
 * @api {post} /app/albums/locations/:locationId/medias/:mediaId/comments Create album location media comment
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocationMediaComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} content Album location media comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.location_media_comment
 *
 * @apiSuccess {Number} data.location_media_comment.id Album location media comment id
 * @apiSuccess {Object} data.location_media_comment.creator Comment creator
 * @apiSuccess {Number} data.location_media_comment.creator.id User create id
 * @apiSuccess {String} data.location_media_comment.creator.full_name User create full_name
 * @apiSuccess {String} data.location_media_comment.creator.email User create email
 * @apiSuccess {String} data.location_media_comment.creator.avatar_url User create avatar image url
 * @apiSuccess {Number} data.location_media_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.location_media_comment.content Comment content
 * @apiSuccess {String} data.location_media_comment.create_at Comment created time
 * @apiSuccess {String} data.location_media_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "location_media_comment": {
 *               "id": 17,
 *               "creator": {
 *                   "id": 1,
 *                   "full_name": "Hoàng Tùng Lâm",
 *                   "email": "hoanglam995@gmail.com",
 *                   "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *               },
 *               "creator_type": 1,
 *               "content": "comment content",
 *               "create_at": "2020-08-17T04:35:11.000000Z",
 *               "update_at": "2020-08-17T04:35:11.000000Z"
 *           }
 *       }
 *   }
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
 * @api {put} /app/albums/locations/medias/:mediaId/comments/:commentId Edit album location media comment
 * @apiVersion 0.1.0
 * @apiName EditAlbumLocationMediaComment
 * @apiGroup Comment for App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} content Album location media comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.location_media_comment
 *
 * @apiSuccess {Number} data.location_media_comment.id Album location media comment id
 * @apiSuccess {Object} data.location_media_comment.creator Comment creator
 * @apiSuccess {Number} data.location_media_comment.creator.id User create id
 * @apiSuccess {String} data.location_media_comment.creator.full_name User create full_name
 * @apiSuccess {String} data.location_media_comment.creator.email User create email
 * @apiSuccess {String} data.location_media_comment.creator.avatar_url User create avatar image url
 * @apiSuccess {Number} data.location_media_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.location_media_comment.content Comment content
 * @apiSuccess {String} data.location_media_comment.create_at Comment created time
 * @apiSuccess {String} data.location_media_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "location_media_comment": {
 *               "id": 17,
 *               "creator": {
 *                   "id": 1,
 *                   "full_name": "Hoàng Tùng Lâm",
 *                   "email": "hoanglam995@gmail.com",
 *                   "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *               },
 *               "creator_type": 1,
 *               "content": "comment content",
 *               "create_at": "2020-08-17T04:35:11.000000Z",
 *               "update_at": "2020-08-17T05:35:11.000000Z"
 *           }
 *       }
 *   }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */
