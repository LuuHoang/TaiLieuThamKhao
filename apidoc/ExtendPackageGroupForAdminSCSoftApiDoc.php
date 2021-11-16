<?php

/**
 * @api {get} /admin/extends Get List Extend Package
 * @apiVersion 0.1.0
 * @apiName GetListExtendPackage
 * @apiGroup Extend Package For Admin SCSoft
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
 * @apiParam {String} sort[user] sort by extend user: ASC - Old, DESC - New
 * @apiParam {String} sort[data] sort by extend data: ASC - Old, DESC - New
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 5,
 *                       "title": "Extend 1",
 *                       "description": "Goi extend 1",
 *                       "extend_user": 0,
 *                       "extend_data": 15,
 *                       "price": 1000,
 *                       "count_company": 0
 *                   }
 *               ],
 *               "total": 5,
 *               "current_page": 1,
 *               "limit": 1
 *           }
 *       }
 */

/**
 * @api {get} /admin/extends/:extendId Get Extend Package
 * @apiVersion 0.1.0
 * @apiName GetExtendPackage
 * @apiGroup Extend Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "extend_data": {
 *                   "id": 5,
 *                   "title": "Extend 1",
 *                   "description": "Goi extend 1",
 *                   "extend_user": 0,
 *                   "price": 1000,
 *                   "extend_data": 15,
 *                   "type": "Extend"
 *               }
 *           }
 *       }
 */

/**
 * @api {post} /admin/extends Create Extend Package
 * @apiVersion 0.1.0
 * @apiName CreateExtendPackage
 * @apiGroup Extend Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title] Title extend package
 * @apiParam {String} [description] Description extend package
 * @apiParam {String} extend_user Extend number user
 * @apiParam {String} extend_data Extend data storage - GB
 * @apiParam {String} price] Price extend package
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "extend_data": {
 *                   "id": 5,
 *                   "title": "Extend 1",
 *                   "description": "Goi extend 1",
 *                   "extend_user": "0",
 *                   "price": "1000",
 *                   "extend_data": 15,
 *                   "type": "Extend"
 *               }
 *           }
 *       }
 */

/**
 * @api {put} /admin/extends/:extendId Update Extend Package
 * @apiVersion 0.1.0
 * @apiName UpdateExtendPackage
 * @apiGroup Extend Package For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [title] Title extend package
 * @apiParam {String} [description] Description extend package
 * @apiParam {String} [extend_user] Extend number user
 * @apiParam {String} [extend_data] Extend data storage - GB
 * @apiParam {String} [price] Price extend package
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "extend_data": {
 *                   "id": 5,
 *                   "title": "Extend 1",
 *                   "description": "Goi extend 1",
 *                   "extend_user": 0,
 *                   "price": 1000,
 *                   "extend_data": 15,
 *                   "type": "Extend"
 *               }
 *           }
 *       }
 */

/**
 * @api {delete} /admin/extends/:extendId Delete Extend Package
 * @apiVersion 0.1.0
 * @apiName DeleteExtendPackage
 * @apiGroup Extend Package For Admin SCSoft
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
