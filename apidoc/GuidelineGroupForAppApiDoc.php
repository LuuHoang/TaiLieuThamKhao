<?php

/**
 * @api {get} /app/guidelines Get List Guideline
 * @apiVersion 0.1.0
 * @apiName GetListGuideline
 * @apiGroup Guideline For App
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {string} search Search keyword
 *
 * @apiSuccess data_list list guideline
 * @apiSuccess data_list.id Guideline id
 * @apiSuccess data_list.title Guideline title
 * @apiSuccess data_list.content Guideline content
 * @apiSuccess data_list.created_at Guideline created at
 * @apiSuccess data_list.information List guideline information
 * @apiSuccess data_list.information.id Guideline information id
 * @apiSuccess data_list.information.title Guideline information title
 * @apiSuccess data_list.information.type Guideline information type: 1 - text, 2 - date, 3 - short date, 4 - date time, 5 - number, 6 - email, 7 - images, 8 - videos, 9 - pdfs
 * @apiSuccess data_list.information.content Guideline information content
 * @apiSuccess data_list.information.files List guideline information files attach
 * @apiSuccess data_list.information.files.id Guideline information files attach id
 * @apiSuccess data_list.information.files.type Guideline information files attach type: 1 - image, 2 - video, 3 - pdf
 * @apiSuccess data_list.information.files.url Guideline information files attach url
 * @apiSuccess data_list.information.files.thumbnail_url Guideline information files attach thumbnail url video
 * @apiSuccess total
 * @apiSuccess current_page
 * @apiSuccess limit
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 1,
 *                       "title": "Guideline title",
 *                       "content": "Guideline content",
 *                       "created_at": "2020-06-23T03:21:58.000000Z",
 *                       "information": [
 *                           {
 *                               "id": 1,
 *                               "title": "Guideline information title",
 *                               "type": 8,
 *                               "content": null,
 *                               "files": [
 *                                     {
 *                                         "id": 1,
 *                                         "type": 2,
 *                                         "url": "http://locathost/video.mp4",
 *                                         "thumbnail_url": "http://locathost/video.png"
 *                                     }
 *                                ],
 *                           }
 *                       ]
 *                   }
 *               ],
 *               "total": 10,
 *               "current_page": 1,
 *               "limit": 1
 *           }
 *       }
 *
 */

/**
 * @api {get} /app/guidelines/:guidelineId Get Guideline Detail
 * @apiVersion 0.1.0
 * @apiName GetGuidelineDetail
 * @apiGroup Guideline For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess data.id Guideline id
 * @apiSuccess data.title Guideline title
 * @apiSuccess data.content Guideline content
 * @apiSuccess data.created_at Guideline created at
 * @apiSuccess data.information List guideline information
 * @apiSuccess data.information.id Guideline information id
 * @apiSuccess data.information.title Guideline information title
 * @apiSuccess data.information.type Guideline information type: 1 - text, 2 - date, 3 - short date, 4 - date time, 5 - number, 6 - email, 7 - images, 8 - videos, 9 - pdfs
 * @apiSuccess data.information.content Guideline information content
 * @apiSuccess data.information.files List guideline information files attach
 * @apiSuccess data.information.files.id Guideline information files attach id
 * @apiSuccess data.information.files.type Guideline information files attach type: 1 - image, 2 - video, 3 - pdf
 * @apiSuccess data.information.files.url Guideline information files attach url
 * @apiSuccess data.information.files.thumbnail_url Guideline information files attach thumbnail url video
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "guideline" : {
 *                   "id": 1,
 *                   "title": "Guideline title",
 *                   "content": "Guideline content",
 *                   "created_at": "2020-06-23T03:21:58.000000Z",
 *                   "information": [
 *                        {
 *                            "id": 1,
 *                            "title": "Guideline information title",
 *                            "type": 8,
 *                            "content": null,
 *                            "files": [
 *                                {
 *                                     "id": 1,
 *                                     "type": 2,
 *                                     "url": "http://locathost/video.mp4",
 *                                     "thumbnail_url": "http://locathost/video.png"
 *                                 }
 *                            ],
 *                        }
 *                    ]
 *               }
 *           }
 *       }
 *
 */
