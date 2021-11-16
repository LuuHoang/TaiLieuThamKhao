<?php

/**
 * @api {get} /app/user/profile Get User Profile
 * @apiVersion 0.1.0
 * @apiName GetUserProfile
 * @apiGroup User For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "user_data": {
 *               "id": 1,
 *               "full_name": "Name",
 *               "email": "hoanglam995@gmail.com",
 *               "address": "Ha Noi",
 *               "avatar_path": "image_path.jpg",
 *               "avatar_url": "http://localhost/image_path.jpg",
 *               "staff_code": "NV001",
 *               "department_id": 1,
 *               "department_title": "Kế Toán",
 *               "position_id": 1,
 *               "position_title": "Trưởng Phòng",
 *               "role": 1,
 *               "company_id": 1,
 *               "company_name": "SC Soft",
 *               "company_code": "SCSOFT",
 *               "package_information": {
 *                   "max_size": 10,
 *                   "package_size": 10,
 *                   "extend_size": 0
 *               },
 *               "user_created_data": null
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
 * @api {get} /app/user/verify Verify User
 * @apiVersion 0.1.0
 * @apiName VerifyUser
 * @apiGroup User For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 1,
 *                   "avatar_path": "avatar.jpg",
 *                   "avatar_url": "http://localhost/AlbumMakerV2/public/storage/users/avatar.jpg",
 *                   "full_name": "Hoàng Lâm",
 *                   "email": "hoanglam995@gmail.com",
 *                   "notification": {
 *                       "unread": 10,
 *                       "total": 20
 *                   }
 *                   "permissions": {
 *                       "share_album": true
 *                   },
 *                   "company_data": {
 *                       "id": 1,
 *                       "company_name": "SC Soft",
 *                       "company_code": "SCSOFT",
 *                       "color": "#444444",
 *                       "logo_path": "1-2020-05-14_03-53-29-537961.png",
 *                       "logo_url": "http://localhost/AlbumMakerV2/public/storage/companies/1-2020-05-14_03-53-29-537961.png",
 *                       "address": "186 Nguyen Tuan",
 *                       "service_id": 1,
 *                       "service_package": "Vip 1",
 *                       "extend_id": null,
 *                       "extend_package": "",
 *                       "max_user": 5,
 *                       "count_user": 5,
 *                       "max_data": 0,
 *                       "count_data": 0,
 *                       "count_sub_user": 0,
 *                       "created_at": "2020-03-19T17:37:55.000000Z",
 *                       "ts_created_at": 1584639475
 *                   },
 *                   "setting_data": {
 *                       "image_size": 2,
 *                       "language": "VI",
 *                       "voice": 1
 *                   }
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
 * @api {get} v2/app/user/profile Get User Profile V2
 * @apiVersion 0.1.0
 * @apiName GetUserProfileV2
 * @apiGroup User For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "user_data": {
 *               "id": 1,
 *               "full_name": "Name",
 *               "email": "hoanglam995@gmail.com",
 *               "address": "Ha Noi",
 *               "avatar_path": "image_path.jpg",
 *               "avatar_url": "http://localhost/image_path.jpg",
 *               "staff_code": "NV001",
 *               "department_id": 1,
 *               "department_title": "Kế Toán",
 *               "position_id": 1,
 *               "position_title": "Trưởng Phòng",
 *               "role": 1,
 *               "company_id": 1,
 *               "company_name": "SC Soft",
 *               "company_code": "SCSOFT",
 *               "package_information": {
 *                   "max_size": 10,
 *                   "package_size": 10,
 *                   "extend_size": 0
 *               },
 *               "user_created_data": null
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
 * @api {get} v2/app/user/verify Verify User V2
 * @apiVersion 0.1.0
 * @apiName VerifyUserV2
 * @apiGroup User For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 1,
 *                   "avatar_path": "avatar.jpg",
 *                   "avatar_url": "http://localhost/AlbumMakerV2/public/storage/users/avatar.jpg",
 *                   "full_name": "Hoàng Lâm",
 *                   "email": "hoanglam995@gmail.com",
 *                   "notification": {
 *                       "unread": 10,
 *                       "total": 20
 *                   }
 *                   "permissions": {
 *                       "share_album": true
 *                   },
 *                   "company_data": {
 *                       "id": 1,
 *                       "company_name": "SC Soft",
 *                       "company_code": "SCSOFT",
 *                       "color": "#444444",
 *                       "logo_path": "1-2020-05-14_03-53-29-537961.png",
 *                       "logo_url": "http://localhost/AlbumMakerV2/public/storage/companies/1-2020-05-14_03-53-29-537961.png",
 *                       "address": "186 Nguyen Tuan",
 *                       "service_id": 1,
 *                       "service_package": "Vip 1",
 *                       "extend_id": null,
 *                       "extend_package": "",
 *                       "max_user": 5,
 *                       "count_user": 5,
 *                       "max_data": 0,
 *                       "count_data": 0,
 *                       "count_sub_user": 0,
 *                       "created_at": "2020-03-19T17:37:55.000000Z",
 *                       "ts_created_at": 1584639475
 *                   },
 *                   "setting_data": {
 *                       "image_size": 2,
 *                       "language": "VI",
 *                       "voice": 1
 *                   }
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
