<?php

/**
 * @api {get} /admin/packages Get List Service Package
 * @apiVersion 0.1.0
 * @apiName GetListServicePackage
 * @apiGroup Service Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search keyword search
 * @apiParam {String} sort[id] sort by id: ASC - Old, DESC - New
 * @apiParam {String} sort[title] sort by title: ASC - Old, DESC - New
 * @apiParam {String} sort[description] sort by description: ASC - Old, DESC - New
 * @apiParam {String} sort[price] sort by price: ASC - Old, DESC - New
 * @apiParam {String} sort[user] sort by max user: ASC - Old, DESC - New
 * @apiParam {String} sort[data] sort by max data: ASC - Old, DESC - New
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 3,
 *                       "title": "Vip 2",
 *                       "description": null,
 *                       "max_user": 500,
 *                       "price": 10000000,
 *                       "max_user_data": 1,
 *                       "max_data": 500,
 *                       "count_company": 0
 *                   },
 *                   {
 *                       "id": 1,
 *                       "title": "Vip 1",
 *                       "description": null,
 *                       "max_user": 6,
 *                       "price": 10000000,
 *                       "max_user_data": 10,
 *                       "max_data": 60,
 *                       "count_company": 1
 *                   }
 *               ],
 *               "total": 2,
 *               "current_page": 1,
 *               "limit": 10
 *           }
 *       }
 */

/**
 * @api {get} /admin/packages/:packageId Get Service Package
 * @apiVersion 0.1.0
 * @apiName GetServicePackage
 * @apiGroup Service Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "package_data": {
 *                   "id": 1,
 *                   "title": "Vip 1",
 *                   "description": null,
 *                   "max_user": 6,
 *                   "price": 10000000,
 *                   "max_user_data": 10,
 *                   "max_data": 60,
 *                   "type": "Package"
 *               }
 *           }
 *       }
 */

/**
 * @api {post} /admin/packages Create Service Package
 * @apiVersion 0.1.0
 * @apiName CreateServicePackage
 * @apiGroup Service Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title service package
 * @apiParam {String} [description] Description service package
 * @apiParam {String} max_user Max user
 * @apiParam {String} max_user_data Max data of 1 user - GB
 * @apiParam {String} price Price service package
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "package_data": {
 *                   "id": 52,
 *                   "title": "VIP 2",
 *                   "description": "Goi vip 2",
 *                   "max_user": "5",
 *                   "price": "100",
 *                   "max_user_data": 10,
 *                   "max_data": 50,
 *                   "type": "Package"
 *               }
 *           }
 *       }
 */

/**
 * @api {put} /admin/packages/:packageId Update Service Package
 * @apiVersion 0.1.0
 * @apiName UpdateServicePackage
 * @apiGroup Service Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [title] Title service package
 * @apiParam {String} [description] Description service package
 * @apiParam {String} [max_user] Max user
 * @apiParam {String} [max_user_data] Max data of 1 user - GB
 * @apiParam {String} [price] Price service package
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "package_data": {
 *                   "id": 1,
 *                   "title": "Vip 1",
 *                   "description": null,
 *                   "max_user": 6,
 *                   "price": 10000000,
 *                   "max_user_data": 10,
 *                   "max_data": 60,
 *                   "type": "Package"
 *               }
 *           }
 *       }
 */

/**
 * @api {delete} /admin/packages/:packageId Delete Service Package
 * @apiVersion 0.1.0
 * @apiName DeleteServicePackage
 * @apiGroup Service Package For Admin SCSoft
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
