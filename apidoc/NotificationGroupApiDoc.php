<?php

/**
 * @api {get} /notifications Get List Notification
 * @apiVersion 0.1.0
 * @apiName GetListNotification
 * @apiGroup Notification
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search keyword search
 *
 * @apiSuccess data_list list notification
 * @apiSuccess data_list.id notification id
 * @apiSuccess data_list.title Notification title
 * @apiSuccess data_list.type Notification type. 1 - comment.
 * @apiSuccess data_list.data Notification data.
 * @apiSuccess data_list.created_time Notification created timestamp
 * @apiSuccess data_list.status Notification read status
 * @apiSuccess total
 * @apiSuccess current_page
 * @apiSuccess limit
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 236,
 *                       "title": "Hoang Lam has comment",
 *                       "type": 1,
 *                       "meta_data": {
 *                             "action_type": 1,  //Create: 1, Update: 2
 *                             "album_id": 1,
 *                             "album_highlight": "abcd",
 *                             "location_id": 1,
 *                             "comment": {
 *                                 "id": 1,
 *                                 "creator": {
 *                                     "id": 1,
 *                                     "full_name": "Hoàng Tùng Lâm",
 *                                     "email": "hoanglam995@gmail.com",
 *                                     "avatar_url": "http://localhost/storage/users/624990.jpeg"
 *                                 },
 *                                 "creator_type": 1, //User: 1, SharedUser: 2
 *                                 "content": "comment content",
 *                                 "create_at": "2020-08-17T04:35:11.000000Z",
 *                                 "update_at": "2020-08-17T04:35:11.000000Z"
 *                             }
 *                        },
 *                       "created_time": 201918276,
 *                       "status": 1
 *                   }
 *               ],
 *               "total": 33,
 *               "current_page": 1,
 *               "limit": 1
 *           }
 *       }
 */

/**
 * @api {get} /notifications/unread Get number notifications unread
 * @apiVersion 0.1.0
 * @apiName GetNotificationUnread
 * @apiGroup Notification
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Number} unread Number notification unread
 * @apiSuccess {Number} total Number notification
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "unread": 10,
 *               "total": 20
 *           }
 *       }
 */

/**
 * @api {patch} /notifications/:notificationId Update status notification
 * @apiVersion 0.1.0
 * @apiName UpdateStatusNotification
 * @apiGroup Notification
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

/**
 * @api {delete} /notifications/:notificationId delete notification
 * @apiVersion 0.1.0
 * @apiName DeleteNotification
 * @apiGroup Notification
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

/**
 * @api {patch} /notifications Update status all notification unread
 * @apiVersion 0.1.0
 * @apiName UpdateStatusAllNotification
 * @apiGroup Notification
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
