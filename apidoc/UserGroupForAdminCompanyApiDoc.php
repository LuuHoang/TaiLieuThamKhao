<?php

/**
 * @api {get} /web/users Get List User
 * @apiVersion 0.1.0
 * @apiName GetListUser
 * @apiGroup Users For Admin company
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} [page] Current page
 * @apiParam {Number} [limit] Item in page
 * @apiParam {String} [search] Keyword search
 * @apiParam {String} [sort[id]] Sort by id: ASC - Up, DESC - Down
 * @apiParam {String} [sort[staff_no]] Sort by staff no: ASC - Up, DESC - Down
 * @apiParam {String} [sort[full_name]] Sort by full name: ASC - Up, DESC - Down
 * @apiParam {String} [sort[email]] Sort by email: ASC - Up, DESC - Down
 * @apiParam {String} [sort[department]] Sort by department: ASC - Up, DESC - Down
 * @apiParam {String} [sort[position]] Sort by position: ASC - Up, DESC - Down
 * @apiParam {String} [sort[album]] Sort by number albums: ASC - Up, DESC - Down
 * @apiParam {String} [sort[data]] Sort by data storage: ASC - Up, DESC - Down
 * @apiParam {String} [filter[role_ids]] Filter by role. Ex ?filter[role_ids]=1,2,3
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 56,
 *                       "staff_code": "NV03",
 *                       "full_name": "Hoàng Lâm",
 *                       "email": "hoanglam99995@gmail.com",
 *                       "address": "Ha Noi",
 *                       "avatar_path": "1589956207.jfif",
 *                       "avatar_url": "http://localhost/AlbumMakerV2/public/storage/users/1589956207.jfif",
 *                       "role": {
 *                           "id": 190,
 *                           "name": "User",
 *                           "description": "",
 *                           "is_admin": false,
 *                           "is_default": true,
 *                           "is_sub_user": false
 *                       },
 *                       "department": "Kế Toán",
 *                       "position": "Trưởng Phòng",
 *                       "number_albums": 0,
 *                       "total_size": 0,
 *                       "max_size": 10,
 *                       "user_created_data": null
 *                   }
 *               ],
 *               "total": 5,
 *               "current_page": 1,
 *               "limit": 1
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
 * @api {get} /web/users/:userId Get User Information
 * @apiVersion 0.1.0
 * @apiName GetUserInformation
 * @apiGroup Users For Admin company
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
 *                   "id": 190,
 *                   "name": "User",
 *                   "description": "",
 *                   "is_admin": false,
 *                   "is_default": true,
 *                   "is_sub_user": false
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
 * @api {post} /web/users Create user
 * @apiVersion 0.1.0
 * @apiName CreateUser
 * @apiGroup Users For Admin company
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [staff_code] Staff no
 * @apiParam {String} full_name Full name
 * @apiParam {String} email Email
 * @apiParam {String} [address] Address
 * @apiParam {String} password Password
 * @apiParam {String} [department] Department
 * @apiParam {String} [position] Position
 * @apiParam {Number} role_id Role id
 * @apiParam {Number} [user_created_id] Id Manager User. Require if sub user
 * @apiParam {String} [avatar_path] Path file avatar
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 50,
 *                   "full_name": "Hoang Lam",
 *                   "email": "hoanglam99555555@gmail.com",
 *                   "address": "Ha Noi",
 *                   "avatar_path": null,
 *                   "avatar_url": null,
 *                   "staff_code": "NV06",
 *                   "department": "Kế Toán",
 *                   "position": "Trưởng Phòng",
 *                   "role": {
 *                       "id": 190,
 *                       "name": "User",
 *                       "description": "",
 *                       "is_admin": false,
 *                       "is_default": true,
 *                       "is_sub_user": false
 *                   },
 *                   "company_id": 34,
 *                   "company_name": "SC Soft Viet Nam",
 *                   "company_code": "SCSOFT",
 *                   "package_information": {
 *                       "max_size": 10,
 *                       "package_size": 10,
 *                       "extend_size": 0
 *                   },
 *                   "user_created_data": null
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
 * @api {put} /web/users/:userId Update user
 * @apiVersion 0.1.0
 * @apiName UpdateUser
 * @apiGroup Users For Admin company
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [staff_code] Staff no
 * @apiParam {String} [full_name] Full name
 * @apiParam {String} [email] Email
 * @apiParam {String} [address] Address
 * @apiParam {String} [password] Password
 * @apiParam {String} [department] Department
 * @apiParam {String} [position] Position
 * @apiParam {Number} [role_id] Role id
 * @apiParam {Number} [user_created_id] Id Manager User
 * @apiParam {String} [avatar_path] Path avatar image
 * @apiParam {Number} [extend_size] Size data extend
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 50,
 *                   "full_name": "Hoang Lam",
 *                   "email": "hoanglam99555555@gmail.com",
 *                   "address": "Ha Noi",
 *                   "avatar_path": null,
 *                   "avatar_url": null,
 *                   "staff_code": "NV06",
 *                   "department": "Kế Toán",
 *                   "position": "Trưởng Phòng",
 *                   "role": {
 *                       "id": 190,
 *                       "name": "User",
 *                       "description": "",
 *                       "is_admin": false,
 *                       "is_default": true,
 *                       "is_sub_user": false
 *                   },
 *                   "company_id": 34,
 *                   "company_name": "SC Soft Viet Nam",
 *                   "company_code": "SCSOFT",
 *                   "package_information": {
 *                       "max_size": 10,
 *                       "package_size": 10,
 *                       "extend_size": 0
 *                   },
 *                   "user_created_data": null
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
 * @api {delete} /web/users/:userId Admin Company Delete User
 * @apiVersion 0.1.0
 * @apiName DeleteUser
 * @apiGroup Users For Admin company
 *
 * @apiParam {Number} user_target_id User manage album
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
 *       "data": "System Error!"
 *     }
 */

/**
 * @api {get} /web/user/export Export users
 * @apiVersion 0.1.0
 * @apiName ExportUser
 * @apiGroup Users For Admin company
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code": 200,
 *       "data": {
 *           "url": "http://localhost/12312123123.xlsx"
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
 * @api {post} /web/user/import import user
 * @apiVersion 0.1.0
 * @apiName ImportUsers
 * @apiGroup Users For Admin company
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file file excel
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "failures": [
 *                   {
 *                       "row": 2,
 *                       "attribute": "Role",
 *                       "errors": [
 *                           "The Role field is required."
 *                       ]
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
