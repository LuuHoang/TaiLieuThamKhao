<?php

/**
 * @api {post} /web/companies/:companyId Update Company Information
 * @apiVersion 0.1.0
 * @apiName UpdateCompany
 * @apiGroup Company For Admin Company
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [company_name] Company name
 * @apiParam {String} [address] Company address
 * @apiParam {String} [color] Company color code
 * @apiParam {File} [logo] Company logo image. Accept JPG, JPEG, PNG image
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "company_data": {
 *                   "id": 1,
 *                   "company_name": "SC Soft",
 *                   "company_code": "SCSOFT",
 *                   "color": "#444444",
 *                   "logo_path": "1-2020-04-28_10-44-11-078558.png",
 *                   "logo_url": "http://localhost/storage/companies/1-2020-04-28_10-44-11-078558.png",
 *                   "address": "186 Nguyen Tuan",
 *                   "service_id": 1,
 *                   "service_package": "Vip 1",
 *                   "extend_id": null,
 *                   "extend_package": "",
 *                   "max_user": 5,
 *                   "count_user": 3,
 *                   "count_sub_user": 0,
 *                   "max_data": 50,
 *                   "count_data": 20,
 *                   "created_at": "2020-03-19T17:37:55.000000Z",
 *                   "ts_created_at": 1584639475
 *               }
 *          }
 *     }
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 422,
 *       "message": "Can not update company information"
 *     }
 */
