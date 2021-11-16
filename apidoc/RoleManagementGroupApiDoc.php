<?php

/**
 * @api {post} /web/roles Create role
 * @apiVersion 0.1.0
 * @apiName CreateRole
 * @apiGroup Role Management For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} name Name role
 * @apiParam {String} [description] Description role
 *
 * @apiParamExample {json} Request-Example:
 *   {
 *      "name": "Cộng tác viên",
 *      "describe": "Cong tac vien"
 *   }
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code":200,
 *       "data": {}
 *   }
 *
 * @apiUse SystemError
 */

/**
 * @api {put} /web/roles/:roleId Update role
 * @apiVersion 0.1.0
 * @apiName UpdateRole
 * @apiGroup Role Management For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [name] Name role
 * @apiParam {String} [description] Description role
 * @apiParam {String} [permissions] Permissions
 *
 * @apiParamExample {json} Request-Example:
 *   {
 *      "name": "Cộng tác viên",
 *      "description": "Cong tac vien",
 *      "permissions": {"web":{"login":0,"module":{"user_manager":0,"album_manager":0,"album_config":0,"guideline_manager":0,"share_album":0,"company_manager":0}},"app":{"login":0,"module":{"share_album":0}},"common":{"sub_user":0,"album_sub_user_manager":0,"album_all_user_manager":0}}
 *   }
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code":200,
 *       "data": {}
 *   }
 *
 * @apiUse SystemError
 */

/**
 * @api {delete} /web/roles/:roleId Delete role
 * @apiVersion 0.1.0
 * @apiName DeleteRole
 * @apiGroup Role Management For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code":200,
 *       "data": {}
 *   }
 *
 * @apiUse SystemError
 */

/**
 * @api {get} /web/roles/:roleId Retrieve role detail
 * @apiVersion 0.1.0
 * @apiName RetrieveRoleDetail
 * @apiGroup Role Management For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code":200,
 *       "data": {
 *           "role": {
 *               "id": 1,
 *               "name": "Cộng tác viên",
 *               "description": "cong tac vien",
 *               "is_admin": false,
 *               "is_default": true,
 *               "permissions": {
 *                   "web": {
 *                       "login": 0,
 *                       "module": {
 *                           "user_manager": 0,
 *                           "album_manager": 0,
 *                           "album_config": 0,
 *                           "guideline_manager": 0,
 *                           "share_album": 0,
 *                           "company_manager": 0
 *                       }
 *                   },
 *                   "app": {
 *                       "login": 0,
 *                       "module": {
 *                           "share_album": 0
 *                       }
 *                   },
 *                   "common": {
 *                       "sub_user": 0,
 *                       "album_sub_user_manager": 0,
 *                       "album_all_user_manager": 0
 *                   }
 *               }
 *           }
 *       }
 *   }
 *
 * @apiUse SystemError
 */

/**
 * @api {get} /web/roles Retrieve list roles
 * @apiVersion 0.1.0
 * @apiName RetrieveListRole
 * @apiGroup Role Management For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} search Keyword search
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 *
 * @apiSuccessExample {json} Success-Response:
 *   {
 *       "code":200,
 *       "data": {
 *           "data_list": [
 *               {
 *                   "id": 1,
 *                   "name": "Cộng tác viên",
 *                   "description": "cong tac vien",
 *                   "is_admin": false,
 *                   "is_default": true
 *               }
 *            ],
 *           "total": 1,
 *           "current_page": 1,
 *           "limit": 10,
 *           "metadata": {}
 *       }
 *   }
 *
 * @apiUse SystemError
 */
