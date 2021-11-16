<?php

/**
 * @api {get} /web/departments Get List departments
 * @apiVersion 0.1.0
 * @apiName GetListDepartment
 * @apiGroup Other For Web
 *
 * @apiSuccess {Object[]} departments List user
 * @apiSuccess {Number} departments.id
 * @apiSuccess {String} departments.title
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "departments": [
 *                   {
 *                       "id": 1,
 *                       "title": "Kế Toán"
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/positions Get List positions
 * @apiVersion 0.1.0
 * @apiName GetListPositions
 * @apiGroup Other For Web
 *
 * @apiSuccess {Object[]} positions List user
 * @apiSuccess {Number} positions.id
 * @apiSuccess {String} positions.title
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "positions": [
 *                   {
 *                       "id": 1,
 *                       "title": "Trưởng Phòng"
 *                   },
 *                   {
 *                       "id": 2,
 *                       "title": "Nhân Viên"
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/packages Get List packages
 * @apiVersion 0.1.0
 * @apiName GetListPackages
 * @apiGroup Other For Web
 *
 * @apiSuccess {Object[]} service_packages List packages
 * @apiSuccess {Number} service_packages.id
 * @apiSuccess {String} service_packages.title
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "service_packages": [
 *                   {
 *                       "id": 1,
 *                       "title": "Vip 1"
 *                   },
 *                   {
 *                       "id": 2,
 *                       "title": "VIP 1"
 *                   },
 *                   {
 *                       "id": 3,
 *                       "title": "Vip 3"
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/extends Get List extend package
 * @apiVersion 0.1.0
 * @apiName GetListExtends
 * @apiGroup Other For Web
 *
 * @apiSuccess {Object[]} extend_packages List packages
 * @apiSuccess {Number} extend_packages.id
 * @apiSuccess {String} extend_packages.title
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "extend_packages": [
 *                   {
 *                       "id": 1,
 *                       "title": "Extend 10"
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /admin/list-company Get List company
 * @apiVersion 0.1.0
 * @apiName GetListCompany
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object[]} companies List packages
 * @apiSuccess {Number} companies.id Id
 * @apiSuccess {String} companies.company_name  Company name
 * @apiSuccess {String} companies.company_code  Company code
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *           "companies": [
 *               {
 *                   "id": 34,
 *                   "company_name": "SC Soft Viet Nam",
 *                   "company_code": "SCSOFT"
 *               },
 *               {
 *                   "id": 41,
 *                   "company_name": "SKT T1",
 *                   "company_code": "NYbD0L"
 *               }
 *           ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/list-users Get List user filter
 * @apiVersion 0.1.0
 * @apiName GetListUserFilter
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object[]} users List users
 * @apiSuccess {Number} users.id Id
 * @apiSuccess {String} users.full_name Full name
 * @apiSuccess {String} users.email Email
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *              "users": [
 *                  {
 *                      "id": 34,
 *                      "full_name": "Nguyen Van A",
 *                      "email": "anv@gmail.com"
 *                  },
 *                  {
 *                      "id": 35,
 *                      "full_name": "Nguyen Van B",
 *                      "email": "bnv@gmail.com"
 *                  },
 *              ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/dashboards Get Dashboards
 * @apiVersion 0.1.0
 * @apiName GetDashBoards
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {number} start_day Start day filter - timestamp
 * @apiParam {number} end_day End day filter - timestamp
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code":200,
 *           "data": {
 *               "dashboard": {
 *                   "data_usage": {
 *                   "number_albums": 1,
 *                   "number_users": 1,
 *                   "used_size": 3,
 *                   "used_size_percent": 30
 *                   },
 *                   "data_top_comments": [
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
 *                           "media": {
 *                               "id": 1,
 *                               "location": {
 *                                   "id": 1,
 *                                   "title": "Phong Khach",
 *                                   "album": {
 *                                       "id": 1,
 *                                       "highlight": "Album 01"
 *                                   }
 *                               }
 *                           },
 *                           "create_at": "2020-08-17T04:35:11.000000Z"
 *                       }
 *                   ],
 *                   "data_top_users": [
 *                       {
 *                           "id": 1,
 *                           "name": "Nhan Vien 1",
 *                           "email": "nv01@gmail.com",
 *                           "number_albums": 1,
 *                           "number_shared_albums": 1,
 *                           "used_size": 3
 *                       }
 *                   ],
 *                   "data_admin_management_users": [ //only admin
 *                       {
 *                           "id": 1,
 *                           "name": "Nhan Vien 1",
 *                           "email": "nv01@gmail.com",
 *                           "avatar": "http://supenient.vn:1018/avatar.jpg",
 *                           "role": 1
 *                       }
 *                   ]
 *               }
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/other/companies/:companyId/roles Retrieve all roles in company
 * @apiVersion 0.1.0
 * @apiName RetrieveListRole
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code":200,
 *       "data": {
 *           "roles": [
 *               {
 *                   "id": 1,
 *                   "name": "Cộng tác viên",
 *                   "description": "cong tac vien",
 *                   "is_admin": false,
 *                   "is_default": true,
 *                   "is_sub_user": true
 *               }
 *            ]
 *       }
 *   }
 *
 * @apiUse SystemError
 */

/**
 * @api {get} /web/companies/:companyId/list-users Get List user of company
 * @apiVersion 0.1.0
 * @apiName GetListUserCompany
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object[]} users List packages
 * @apiSuccess {Number} users.id Id
 * @apiSuccess {String} users.full_name Full name
 * @apiSuccess {String} users.email Email
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *              "users": [
 *                  {
 *                      "id": 34,
 *                      "full_name": "Nguyen Van A",
 *                      "email": "anv@gmail.com"
 *                  },
 *                  {
 *                      "id": 35,
 *                      "full_name": "Nguyen Van B",
 *                      "email": "bnv@gmail.com"
 *                  },
 *              ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/pdf/templates Get template pdf of page content
 * @apiVersion 0.1.0
 * @apiName GetPDFTemplateOfPageContent
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *              "templates": [
 *                  {
 *                      "id": 34,
 *                      "name": "Template 1",
 *                      "description": "",
 *                      "html": "<html lang='en'><body>...</body></html>",
 *                      "image_no": 1
 *                  }
 *              ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/other/pdf/formats Get List pdf format
 * @apiVersion 0.1.0
 * @apiName GetListPdfFormat
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "formats": [
 *                   {
 *                       "id": 1,
 *                       "title": "Album pdf format title",
 *                       "description": "Album pdf format description",
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /web/all-users Get List all users
 * @apiVersion 0.1.0
 * @apiName GetListAllUser
 * @apiGroup Other For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object[]} users List users
 * @apiSuccess {Number} users.id Id
 * @apiSuccess {String} users.full_name Full name
 * @apiSuccess {String} users.email Email
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *              "users": [
 *                  {
 *                      "id": 34,
 *                      "full_name": "Nguyen Van A",
 *                      "email": "anv@gmail.com"
 *                  },
 *                  {
 *                      "id": 35,
 *                      "full_name": "Nguyen Van B",
 *                      "email": "bnv@gmail.com"
 *                  },
 *              ]
 *           }
 *       }
 *
 */
