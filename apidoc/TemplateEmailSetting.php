<?php

/**
 * @api {get} /template/emails?page=&limit= Retrieve email template list
 * @apiName Retrieve email template list
 * @apiGroup Template Setting
 *
 * @apiParam {Number} page
 * @apiParam {Number} limit
 *
 * @apiSuccessExample {json} Success-Response:
 *  {
 *      "code": 200,
 *      "data": {
 *          "data_list": [
 *              {
 *                  "id": 1,
 *                  "title": "Template name",
 *                  "default": 1,
 *                  "created_at": "2020-12-12 12:12:12",
 *                  "created_user": {
 *                      "id": 1,
 *                      "name": "Name",
 *                      "mail": "mail@gmail.com"
 *                  },
 *                  "cc_list": [
 *                      "abc@gmail.com",
 *                      "abc1@gmail.com"
 *                  ],
 *                  "bcc": [
 *                      "abc@gmail.com",
 *                      "abc1@gmail.com"
 *                  ]
 *              }
 *          ],
 *          "total": 10,
 *          "current_page": 10,
 *          "limit": 10
 *      }
 *  }
 */

/**
 * @api {post} /template/emails Register template email
 * @apiName Register template email
 * @apiGroup Template Setting
 *
 * @apiParamExample {json} Request-Example:
 *  {
 *      "title": "title",
 *      "default": 1,
 *      "cc_list": [
 *          "abc@gmail.com",
 *          "abc1@gmail.com"
 *      ],
 *      "bcc": [
 *          "abc@gmail.com",
 *          "abc1@gmail.com"
 *      ],
 *      "content": "Content"
 *  }
 */

/**
 * @api {get} /template/emails/:id Get email template detail
 * @apiName Get email template detail
 * @apiGroup Template Setting
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "email_template_data": {
 *              "id": 1,
 *              "title": "title",
 *              "default": 1,
 *              "cc_list": [
 *                  "abc@gmail.com",
 *                  "abc1@gmail.com"
 *              ],
 *              "bcc": [
 *                  "abc@gmail.com",
 *                  "abc1@gmail.com"
 *              ],
 *              "subject": "test",
 *              "content": "Content",
 *              "created_at": "2020-12-12 12:12:12",
 *              "updated_at": "2020-12-12 12:12:12",
 *              "created_user": {
 *                  "id": 1,
 *                  "name": "Name",
 *                  "email": "email@gmail.com"
 *              },
 *              "updated_user": {
 *                  "id": 1,
 *                  "name": "Name",
 *                  "email": "email@gmail.com"
 *              }
 *          }
 *      }
 *  }
 */

/**
 * @api {delete} /template/emails/:id Delete template email
 * @apiName Delete template email
 * @apiGroup Template Setting
 *
 * @apiSuccessExample {json} Success-Example
 *  {
 *      "code": 200,
 *      "data": {}
 *  }
 */

/**
 * @api {put} /template/emails/:id Update template email
 * @apiName Update template email
 * @apiGroup Template Setting
 *
 * @apiParamExample {json} Request-Example:
 *  {
 *      "title": "title",
 *      "default": 1,
 *      "subject": "Subject",
 *      "cc_list": [
 *          "abc@gmail.com",
 *          "abc1@gmail.com"
 *      ],
 *      "bcc": [
 *          "abc@gmail.com",
 *          "abc1@gmail.com"
 *      ],
 *      "content": "Content",
 *      "updated_at": "2020-12-12 12:12:12"
 *  }
 */

/**
 * @api {get} /template/emails/all Get all template emails
 * @apiName Get all template emails
 * @apiGroup Template Setting
 *
 * @apiSuccessExample {json} Success-Example
 *  {
 *      "code": 200,
 *      "data": {
 *          "data_list": [
 *              {
 *                  "id": 1,
 *                  "title": "Template email 1",
 *                  "default": 0,
 *                  "created_at": "2021-01-14 09:38:35",
 *                  "updated_at": "2021-01-14 10:02:17",
 *                  "created_user": {
 *                      "id": 6,
 *                      "name": "Hung Hoang",
 *                      "email": "abc@gmail.com"
 *                  },
 *                  "cc_list": [
 *                      "tung.nguyen@supenient.vn",
 *                      "dat.truong@supenient.vn"
 *                  ],
 *                  "bcc_list": [
 *                      "quang.kieu@supenient.vn",
 *                      "thuy.do@supenient.vn"
 *                  ]
 *              }
 *          ]
 *      }
 *  }
 */

/**
 * @api {get} /template/emails/config Get template email config
 * @apiName Get template email config
 * @apiGroup Template setting
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "company": {
 *              "company.company_name": "SCSOFT SUPENIENT",
 *              "company.company_code": "sotdmy",
 *              "company.address": "Nguyen Tuan, Thanh Xuan , Ha Noi",
 *              "company.representative": null,
 *              "company.tax_code": null
 *          },
 *          "guest": {
 *              "guest.name": "messages.sample_guest_name",
 *              "guest.email": "messages.sample_guest_email",
 *              "guest.content": "messages.sample_guest_content"
 *          }
 *      }
 *  }
 */

/**
 * @api {patch} /template/emails/:id Update template email default
 * @apiName Update template email default
 * @apiGroup Template setting
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {}
 *  }
 */

/**
 * @api {get} /template/emails/sample Get template sample
 * @apiName Get template sample
 * @apiGroup Template setting
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "sample": "sample
 *      }
 *  }
 */
