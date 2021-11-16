<?php

/**
 * @api {get} /web/guidelines Get List Guideline
 * @apiVersion 0.1.0
 * @apiName GetListGuideline
 * @apiGroup Guideline For Web
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
 * @api {get} /web/guidelines/:guidelineId Get Guideline Detail
 * @apiVersion 0.1.0
 * @apiName GetGuidelineDetail
 * @apiGroup Guideline For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess data.guideline.id Guideline id
 * @apiSuccess data.guideline.title Guideline title
 * @apiSuccess data.guideline.content Guideline content
 * @apiSuccess data.guideline.created_at Guideline created at
 * @apiSuccess data.guideline.information List guideline information
 * @apiSuccess data.guideline.information.id Guideline information id
 * @apiSuccess data.guideline.information.title Guideline information title
 * @apiSuccess data.guideline.information.type Guideline information type: 1 - text, 2 - date, 3 - short date, 4 - date time, 5 - number, 6 - email, 7 - images, 8 - videos, 9 - pdfs
 * @apiSuccess data.guideline.information.content Guideline information content
 * @apiSuccess data.guideline.information.files List guideline information files attach
 * @apiSuccess data.guideline.information.files.id Guideline information files attach id
 * @apiSuccess data.guideline.information.files.type Guideline information files attach type: 1 - image, 2 - video, 3 - pdf
 * @apiSuccess data.guideline.information.files.url Guideline information files attach url
 * @apiSuccess data.guideline.information.files.thumbnail_url Guideline information files attach thumbnail url video
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

/**
 * @api {post} /web/guidelines Create Guideline
 * @apiVersion 0.1.0
 * @apiName CreateGuideline
 * @apiGroup Guideline For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title guideline
 * @apiParam {String} content Content guideline
 * @apiParam {Object[]} [information] Guideline information
 * @apiParam {String} information.title Title guideline information
 * @apiParam {Number} information.type Type guideline information: 1 - text, 2 - date, 3 - short date, 4 - date time, 5 - number, 6 - email, 7 - images, 8 - videos, 9 - pdfs
 * @apiParam information.content Content guideline information. Accept string or array file upload
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

/**
 * @api {post} /web/guidelines/:guidelineId Update Guideline
 * @apiVersion 0.1.0
 * @apiName UpdateGuideline
 * @apiGroup Guideline For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [title] Title guideline
 * @apiParam {String} [content] Content guideline
 * @apiParam {Object[]} [information] Guideline information
 * @apiParam {String} [information.id] Id guideline information. require if update guideline information
 * @apiParam {String} [information.title] Title guideline information
 * @apiParam {Number} [information.type] Type guideline information: 1 - text, 2 - date, 3 - short date, 4 - date time, 5 - number, 6 - email, 7 - images, 8 - videos, 9 - pdfs. Not allow with update guideline information
 * @apiParam [information.content] Content guideline information. Accept string or array file upload
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

/**
 * @api {delete} /web/guidelines/:guidelineId Delete Guideline
 * @apiVersion 0.1.0
 * @apiName DeleteGuideline
 * @apiGroup Guideline For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
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
 *
 */

/**
 * @api {delete} /web/guidelines/:guidelineId/information/:informationId Delete Guideline Information
 * @apiVersion 0.1.0
 * @apiName DeleteGuidelineInformation
 * @apiGroup Guideline For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
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
 *
 */

/**
 * @api {delete} /web/guidelines/:guidelineId/information/:informationId/medias/:mediaId Delete Guideline Information Media
 * @apiVersion 0.1.0
 * @apiName DeleteGuidelineInformationMedia
 * @apiGroup Guideline For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
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
 *
 */
