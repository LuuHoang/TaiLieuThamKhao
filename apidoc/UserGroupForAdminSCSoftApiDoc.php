<?php

/**
 * @api {post} /admin/users Create user
 * @apiVersion 0.1.0
 * @apiName CreateUser
 * @apiGroup User For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} company_id Company id
 * @apiParam {String} [staff_code] Staff no
 * @apiParam {String} full_name Full name
 * @apiParam {String} email Email
 * @apiParam {String} [address] Address
 * @apiParam {String} password Password
 * @apiParam {String} [department] Department
 * @apiParam {String} [position] Position
 * @apiParam {Number} role_id Role id
 * @apiParam {Number} [user_created_id] Id Manager User. Require if role is sub user
 * @apiParam {String} [avatar_path] Path file avatar
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 58,
 *                   "full_name": "Hoang Lam",
 *                   "email": "hoanglam99555555@gmail.com",
 *                   "address": "Ha Noi",
 *                   "avatar_path": null,
 *                   "avatar_url": null,
 *                   "staff_code": "NV06",
 *                   "department": "SCSoft",
 *                   "position": "12 Khuất duy tiến",
 *                   "role": {
 *                        "id": 58,
 *                        "name": "Admin",
 *                        "description": "",
 *                        "is_admin": true,
 *                        "is_default": true,
 *                        "is_sub_user": false
 *                    },
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
 * @api {put} /admin/users/:userId Update user
 * @apiVersion 0.1.0
 * @apiName UpdateUser
 * @apiGroup User For Admin SCSoft
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
 * @apiParam {Number} [role] Role: 1- Admin, 2 - User, 3 - Sub User
 * @apiParam {Number} [user_created_id] Id Manager User. Require if role = 3 ~ Sub User
 * @apiParam {String} [avatar_path] Path file avatar
 * @apiParam {Number} [extend_size] Size data extend
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 58,
 *                   "full_name": "Hoang Lam",
 *                   "email": "hoanglam99555555@gmail.com",
 *                   "address": "Ha Noi",
 *                   "avatar_path": null,
 *                   "avatar_url": null,
 *                   "staff_code": "NV06",
 *                   "department": "SCSoft",
 *                   "position": "12 Khuất duy tiến",
 *                   "role": {
 *                        "id": 58,
 *                        "name": "Admin",
 *                        "description": "",
 *                        "is_admin": true,
 *                        "is_default": true,
 *                        "is_sub_user": false
 *                    },
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
 * @api {get} /admin/users/:userId Get user detail
 * @apiVersion 0.1.0
 * @apiName GetUser
 * @apiGroup User For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "user_data": {
 *                   "id": 58,
 *                   "full_name": "Hoang Lam",
 *                   "email": "hoanglam99555555@gmail.com",
 *                   "address": "Ha Noi",
 *                   "avatar_path": null,
 *                   "avatar_url": null,
 *                   "staff_code": "NV06",
 *                   "department": "SCSoft",
 *                   "position": "12 Khuất duy tiến",
 *                   "role": {
 *                        "id": 58,
 *                        "name": "Admin",
 *                        "description": "",
 *                        "is_admin": true,
 *                        "is_default": true,
 *                        "is_sub_user": false
 *                    },
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
 * @api {delete} /admin/users/:userId Delete user
 * @apiVersion 0.1.0
 * @apiName DeleteUser
 * @apiGroup User For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
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
 * @api {get} /admin/users Get All Users
 * @apiVersion 0.1.0
 * @apiName GetAllUser
 * @apiGroup User For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} [page] Current page
 * @apiParam {Number} [limit] item in page
 * @apiParam {String} [search] keyword search
 * @apiParam {String} [sort[id]] sort by id: ASC - Old, DESC - New
 * @apiParam {String} [sort[staff_no]] sort by staff no: ASC - Old, DESC - New
 * @apiParam {String} [sort[full_name]] sort by full name: ASC - Old, DESC - New
 * @apiParam {String} [sort[email]] sort by email: ASC - Old, DESC - New
 * @apiParam {String} [sort[company]] sort by company name: ASC - Old, DESC - New
 * @apiParam {String} [sort[department]] sort by department: ASC - Old, DESC - New
 * @apiParam {String} [sort[position]] sort by position: ASC - Old, DESC - New
 * @apiParam {String} [sort[album]] sort by number album: ASC - Old, DESC - New
 * @apiParam {String} [sort[data]] sort data storage: ASC - Old, DESC - New
 * @apiParam {String} [filter[company_ids]] Filter by company. Ex ?filter[company_ids]=1,2,3
 * @apiParam {String} [filter[user_types]] Filter by user type. Ex ?filter[user_types]=1,2,3
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 45,
 *                       "full_name": "Hoang Lam",
 *                       "email": "hoanglam995@gmail.com",
 *                       "address": "Ha Noi",
 *                       "avatar_path": null,
 *                       "avatar_url": null,
 *                       "staff_code": "NV01",
 *                       "department": "Kế Toán",
 *                       "position": "Trưởng Phòng",
 *                       "role": 1,
 *                       "company_data": {
 *                           "company_id": 34,
 *                           "company_name": "SC Soft Viet Nam",
 *                           "company_code": "SCSOFT"
 *                       },
 *                       "usage_data": {
 *                           "count_album": 0,
 *                           "count_data": 1,
 *                           "package_data": 10,
 *                           "extend_data": 0
 *                       },
 *                       "user_created_data": null
 *                   }
 *               ],
 *               "total": 1,
 *               "current_page": 1,
 *               "limit": 10
 *           }
 *       }
 *
 */
