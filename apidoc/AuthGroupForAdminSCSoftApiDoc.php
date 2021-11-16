<?php

/**
 * @api {get} /admin/verify Verify Admin
 * @apiVersion 0.1.0
 * @apiName VerifyAdmin
 * @apiGroup Auth For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "admin_data": {
 *                   "id": 1,
 *                   "full_name": "Admin",
 *                   "email": "admin@gmail.com",
 *                   "avatar_path": "avatar.png"
 *               },
 *               "company_data": {
 *                   "company_name": "SC Soft",
 *                   "Logo_path": "logo.png"
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
 * @api {post} /admin/login Login Admin
 * @apiVersion 0.1.0
 * @apiName LoginAdmin
 * @apiGroup Auth For Admin SCSoft
 *
 * @apiParam {String} email Email
 * @apiParam {String} password Password
 *
 * @apiSuccess {Object} data
 *
 * @apiSuccess {Object} data.admin_data
 * @apiSuccess {Integer} data.admin_data.id Admin - ID
 * @apiSuccess {String} data.admin_data.full_name Admin name
 * @apiSuccess {String} data.admin_data.email Admin email
 * @apiSuccess {String} data.admin_data.avatar_path Admin image path
 * @apiSuccess {String} data.admin_data.avatar_url Admin image url
 *
 * @apiSuccess {Object} data.company_data Company data
 * @apiSuccess {String} [data.company_data.company_name] Company name
 * @apiSuccess {String} [data.company_data.logo_path] Company logo path
 * @apiSuccess {String} [data.company_data.address] Company address
 *
 * @apiSuccess {String} data.admin_token Admin token
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "admin_data": {
 *                   "id": 1,
 *                   "full_name": "Hoang Lam",
 *                   "email": "admin@gmail.com",
 *                   "avatar_path": "avatar.png",
 *                   "avatar_url": "http://avatar.png",
 *               },
 *               "company_data": {
 *                   "company_name": "SC Soft",
 *                   "logo_path": "logo.png",
 *                   "address": "178 Nguyen Tuan"
 *               }
 *               "admin_token": "$2y$10$dL4moCIB2NsqY9iXlrWpaeS5U8MwIn4Nr2gxZHTauO4uVd3G8aI.u"
 *           }
 *       }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *         "code": 401,
 *         "message": "Wrong email or password!"
 *     }
 */

/**
 * @api {post} /admin/logout Admin Logout
 * @apiVersion 0.1.0
 * @apiName adminLogout
 * @apiGroup Auth For Admin SCSoft
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
 * @api {post} /admin/create Create Admin
 * @apiVersion 0.1.0
 * @apiName createAdmin
 * @apiGroup Auth For Admin SCSoft
 *
 * @apiUse HeaderToken
 * @apiParam {String} full_name Full Name
 * @apiParam {String} email Email
 * @apiParam {String} password Password
 * @apiParam {File}  [avatar] Avatar
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *          "admin": {
 *                  "id": 4,
 *                  "full_name":Lê Văn Lương,
 *                  "email":levanluong@gmail.com,
 *                  "avatar_path":"1/16149357621.png",
 *                  "avatar_url":"http://127.0.0.1:8000/storage/admins/1/16149357621.png",
 *              }
 *          }
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
 * @api {post} /admin/update/:adminId Update Admin
 * @apiVersion 0.1.0
 * @apiName updateAdmin
 * @apiGroup Auth For Admin SCSoft
 *
 * @apiUse HeaderToken
 * @apiParam {Number} id is adminId
 * @apiParam {String} full_name Full Name
 * @apiParam {String} [email] Email
 * @apiParam {String} [password] Password
 * @apiParam {File}  [avatar] Avatar
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "admin": {
     *              "id": 4,
     *              "full_name":Lê Văn Lương,
     *              "email":"levanluong@gmail.com",
     *              "avatar_path": "1/16149357621.png",
     *              "avatar_url":"http://127.0.0.1:8000/storage/admins/1/16149357621.png",
 *                  }
 *          }
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
 * @api {post} /admin/delete/:adminId/:responsibleAdminId Delete Admin
 * @apiVersion 0.1.0
 * @apiName deleteAdmin
 * @apiGroup Auth For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *
 *
 *          }
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
 * @api {post} /admin/list Get List Admin
 * @apiVersion 0.1.0
 * @apiName List Admin
 * @apiGroup Auth For Admin SCSoft
 *
 * @apiHeader {String} token_access : token of Admin
 *
 * @apiParam {Number} [limit]  is page limit
 * @apiParam {Number} [page]  is page
 * @apiParam {Text} [search]  is Key Search
 * @apiParam {Array} [sort]  is array sort
 * @apiParam {String} [sort.id] is sort by id |asc|desc|
 * @apiParam {String} [sort.full_name] is sort |asc|desc|
 * @apiParam {String} [sort.email] is sort by email |asc|desc|
 *
 * @apiSuccessExample {json} Success-Example:
 * {
        "code": 200,
            "data": {
                "data_list": [
                    {
                        "id": 1,
                        "full_name": "Hung Hoang",
                        "email": "abc@gmail.com",
                        "avatar_path": "",
                        "avatar_url": "http://127.0.0.1:8000/storage/admins/"
                    },
                    {
                        "id": 2,
                        "full_name": "Lưu Hoàng",
                        "email": "hoang@gmail.com",
                        "avatar_path": "",
                        "avatar_url": "http://127.0.0.1:8000/storage/admins/"
                    },
                    {
                        "id": 3,
                        "full_name": "luuhoang",
                        "email": "hoangluu2508@gmail.com",
                        "avatar_path": "",
                        "avatar_url": "http://127.0.0.1:8000/storage/admins/"
                    },
                    {
                        "id": 5,
                        "full_name": "LeVanLang",
                        "email": "leluo@gmail.com",
                        "avatar_path": "1/16149357621.png",
                        "avatar_url": "http://127.0.0.1:8000/storage/admins/1/16149357621.png"
                    },
                    {
                        "id": 4,
                        "full_name": "LeVanLuong",
                        "email": "levanluong@gmail.com",
                        "avatar_path": "1/16149302001.png",
                        "avatar_url": "http://127.0.0.1:8000/storage/admins/1/16149302001.png"
                    }
                ],
                "total": 5,
                "current_page": 1,
                "limit": 10
            }
        }
 */
