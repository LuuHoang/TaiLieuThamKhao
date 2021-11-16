<?php

/**
 * @api {get} /web/albums/:albumId/locations/:locationId/comments Get album location comment
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationComment
 * @apiGroup Comment for Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
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
 * @api {post} /web/albums/:albumId/locations/:locationId/comments Create album location comment for user
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocationComment
 * @apiGroup Comment for Web
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
 * @api {put} /web/albums/locations/:locationId/comments/:commentId Edit Album Location comment for user
 * @apiVersion 0.1.0
 * @apiName EditAlbumLocationComment
 * @apiGroup Comment for Web
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
 * @api {get} /web/albums/locations/:locationId/medias/:mediaId/comments Get album location media comment
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationMediaComment
 * @apiGroup Comment for Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
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
 * @api {post} /web/albums/locations/:locationId/medias/:mediaId/comments Create album location media comment for user
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocationMediaComment
 * @apiGroup Comment for Web
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
 * @api {put} /web/albums/locations/medias/:mediaId/comments/:commentId Edit album location media comment for user
 * @apiVersion 0.1.0
 * @apiName EditAlbumLocationMediaComment
 * @apiGroup Comment for Web
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

/**
 * @api {post} /web/shared/albums/:albumId/locations/:locationId/comments/list Get album location comment for share user
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationCommentForShareUser
 * @apiGroup Comment for Web
 *
 * @apiParam {String} token Token authenticate - form data
 * @apiParam {String} password Password authenticate - form data
 *
 * @apiParam {Number} page Query param current page. Ex: ?page=1
 * @apiParam {Number} limit Query param limit item in page. Ex: ?limit=10
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
 * @api {post} /web/shared/albums/:albumId/locations/:locationId/comments Create album location comment for share user
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocationCommentForShareUser
 * @apiGroup Comment for Web
 *
 * @apiParam {String} token Token authenticate
 * @apiParam {String} password Password authenticate
 * @apiParam {String} content Album location comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.album_location_comment
 *
 * @apiSuccess {Number} data.album_location_comment.id Album location comment id
 * @apiSuccess {Object} data.album_location_comment.creator Comment creator
 * @apiSuccess {Number} data.album_location_comment.creator.shared_album_id Share album id
 * @apiSuccess {String} data.album_location_comment.creator.full_name Share user full_name
 * @apiSuccess {String} data.album_location_comment.creator.email Share user email
 * @apiSuccess {Number} data.album_location_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.album_location_comment.content Comment content
 * @apiSuccess {String} data.album_location_comment.create_at comment created time
 * @apiSuccess {String} data.album_location_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_location_comment": {
 *                   "id": 22,
 *                   "creator": {
 *                       "shared_album_id": 3,
 *                       "full_name": "Hoang Tung Lam",
 *                       "email": "hoanglam995@gmail.com"
 *                   },
 *                   "creator_type": 2,
 *                   "content": "comment content",
 *                   "create_at": "2020-08-19T03:31:30.000000Z",
 *                   "update_at": "2020-08-19T03:31:30.000000Z"
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
 * @api {put} /web/shared/albums/locations/:locationId/comments/:commentId Edit Album Location comment for share user
 * @apiVersion 0.1.0
 * @apiName EditAlbumLocationCommentForShareUser
 * @apiGroup Comment for Web
 *
 * @apiParam {String} token Token authenticate
 * @apiParam {String} password Password authenticate
 * @apiParam {String} content Album location comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.album_location_comment
 *
 * @apiSuccess {Number} data.album_location_comment.id Album location comment id
 * @apiSuccess {Object} data.album_location_comment.creator Comment creator
 * @apiSuccess {Number} data.album_location_comment.creator.shared_album_id Share album id
 * @apiSuccess {String} data.album_location_comment.creator.full_name Share user full_name
 * @apiSuccess {String} data.album_location_comment.creator.email Share user email
 * @apiSuccess {Number} data.album_location_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.album_location_comment.content Comment content
 * @apiSuccess {String} data.album_location_comment.create_at comment created time
 * @apiSuccess {String} data.album_location_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_location_comment": {
 *                   "id": 22,
 *                   "creator": {
 *                       "shared_album_id": 3,
 *                       "full_name": "Hoang Tung Lam",
 *                       "email": "hoanglam995@gmail.com"
 *                   },
 *                   "creator_type": 2,
 *                   "content": "comment content",
 *                   "create_at": "2020-08-19T03:31:30.000000Z",
 *                   "update_at": "2020-08-19T03:31:30.000000Z"
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
 * @api {post} /web/shared/albums/locations/:locationId/medias/:mediaId/comments/list Get album location media comment for share user
 * @apiVersion 0.1.0
 * @apiName GetAlbumLocationMediaCommentForShareUser
 * @apiGroup Comment for Web
 *
 * @apiParam {String} token Token authenticate - form data
 * @apiParam {String} password Password authenticate - form data
 *
 * @apiParam {Number} page Query param current page. Ex: ?page=1
 * @apiParam {Number} limit Query param limit item in page. Ex: ?limit=10
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
 * @api {post} /web/shared/albums/locations/:locationId/medias/:mediaId/comments Create album location media comment for share user
 * @apiVersion 0.1.0
 * @apiName CreateAlbumLocationMediaCommentForShareUser
 * @apiGroup Comment for Web
 *
 * @apiParam {String} token Token authenticate
 * @apiParam {String} password Password authenticate
 * @apiParam {String} content Album location comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.location_media_comment
 *
 * @apiSuccess {Number} data.location_media_comment.id Album location media comment id
 * @apiSuccess {Object} data.location_media_comment.creator Comment creator
 * @apiSuccess {Number} data.location_media_comment.creator.shared_album_id Share album id
 * @apiSuccess {String} data.location_media_comment.creator.full_name Share user full_name
 * @apiSuccess {String} data.location_media_comment.creator.email Share user email
 * @apiSuccess {Number} data.location_media_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.location_media_comment.content Comment content
 * @apiSuccess {String} data.location_media_comment.create_at Comment created time
 * @apiSuccess {String} data.location_media_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "location_media_comment": {
 *                   "id": 23,
 *                   "creator": {
 *                       "shared_album_id": 3,
 *                       "full_name": "Hoang Tung Lam",
 *                       "email": "hoanglam995@gmail.com"
 *                   },
 *                   "creator_type": 2,
 *                   "content": "comment content",
 *                   "create_at": "2020-08-19T03:40:24.000000Z",
 *                   "update_at": "2020-08-19T03:40:24.000000Z"
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
 * @api {put} /web/shared/albums/locations/medias/:mediaId/comments/:commentId Edit album location media comment for share user
 * @apiVersion 0.1.0
 * @apiName EditAlbumLocationMediaCommentForShareUser
 * @apiGroup Comment for Web
 *
 * @apiParam {String} token Token authenticate
 * @apiParam {String} password Password authenticate
 * @apiParam {String} content Album location comment content
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.location_media_comment
 *
 * @apiSuccess {Number} data.location_media_comment.id Album location media comment id
 * @apiSuccess {Object} data.location_media_comment.creator Comment creator
 * @apiSuccess {Number} data.location_media_comment.creator.shared_album_id Share album id
 * @apiSuccess {String} data.location_media_comment.creator.full_name Share user full_name
 * @apiSuccess {String} data.location_media_comment.creator.email Share user email
 * @apiSuccess {Number} data.location_media_comment.creator_type Creator type: 1 - user, 2- shared user
 * @apiSuccess {String} data.location_media_comment.content Comment content
 * @apiSuccess {String} data.location_media_comment.create_at Comment created time
 * @apiSuccess {String} data.location_media_comment.update_at Comment updated time
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "location_media_comment": {
 *                   "id": 23,
 *                   "creator": {
 *                       "shared_album_id": 3,
 *                       "full_name": "Hoang Tung Lam",
 *                       "email": "hoanglam995@gmail.com"
 *                   },
 *                   "creator_type": 2,
 *                   "content": "comment content",
 *                   "create_at": "2020-08-19T03:40:24.000000Z",
 *                   "update_at": "2020-08-19T03:40:24.000000Z"
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
