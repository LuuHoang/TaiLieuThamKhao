<?php

/**
 * @api {get} /web/user/profile Get User Profile
 * @apiVersion 0.1.0
 * @apiName GetUserProfile
 * @apiGroup User For Web
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
 *               "department": "Kế Toán",
 *               "position": "Trưởng Phòng",
 *               "role": {
 *                       "id": 190,
 *                       "name": "User",
 *                       "description": "",
 *                       "is_admin": false,
 *                       "is_default": true,
 *                       "is_sub_user": false
 *               },
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
 * @api {put} /web/user/profile Update User Profile
 * @apiVersion 0.1.0
 * @apiName UpdateUserProfile
 * @apiGroup User For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [staff_code] staff code. Maximum length of 8 characters
 * @apiParam {Number} [full_name] full name
 * @apiParam {String} [email] email
 * @apiParam {String} [address] Address
 * @apiParam {String} [avatar_path] path avatar image
 * @apiParam {String} [department]  Department
 * @apiParam {String} [position]  position
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *              "staff_code" : "NV001",
 *              "full_name" : "Name",
 *              "email" : "abc@gmail.com",
 *              "address" : "Ha Noi"
 *              "avatar_path" : "image_path.jpg",
 *              "department" : "Kế Toán",
 *              "position" : "Trưởng Phòng"
 *       }
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "user_data": {
 *               "id": 1,
 *               "full_name": "Name",
 *               "email": "abc@gmail.com",
 *               "address": "Ha Noi",
 *               "avatar_path": "image_path.jpg",
 *               "avatar_url": "http://localhost/image_path.jpg",
 *               "staff_code": "NV001",
 *               "department": "Kế Toán",
 *               "position": "Trưởng Phòng",
 *               "role": {
 *                       "id": 190,
 *                       "name": "User",
 *                       "description": "",
 *                       "is_admin": false,
 *                       "is_default": true,
 *                       "is_sub_user": false
 *               },
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
 * @api {get} /web/user/verify Verify User
 * @apiVersion 0.1.0
 * @apiName VerifyUser
 * @apiGroup User For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 1,
 *                   "full_name": "Hoàng Lâm",
 *                   "email": "lam.hoang@supenien.vn",
 *                   "department": "Kế Toán",
 *                   "position": "Trưởng Phòng",
 *                   "avatar_path": "avatar.jpg",
 *                   "avatar_url": "http://localhost/AlbumMakerV2/public/storage/users/avatar.jpg",
 *                   "role": {
 *                       "id": 190,
 *                       "name": "User",
 *                       "description": "",
 *                       "is_admin": false,
 *                       "is_default": true,
 *                       "is_sub_user": false
 *                   },
 *                   "permissions": {
 *                       "user_manager": false,
 *                       "album_manager": false,
 *                       "album_config": false,
 *                       "guideline_manager": false,
 *                       "share_album": false,
 *                       "company_manager": false
 *                    },
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
