<?php
/**
 * @api {get} /admin/all GET ALL Users Are Admin
 * @apiVersion 0.1.0
 * @apiName GETALLUsersAreAdmin
 * @apiGroup Admin
 *
 * @apiHeader {String} token_access : token of Admin
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "list_user": [
 *               {
 *                   "id": 1,
 *                   "full_name": "Hung Hoang"
 *               },
 *               {
 *                   "id": 2,
 *                   "full_name": "Lưu Hoàng"
 *               }
 *           ]
 *
 *      }
 *  }
 */
