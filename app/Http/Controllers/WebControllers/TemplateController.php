<?php

namespace App\Http\Controllers\WebControllers;

use App\Constants\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebRequests\TemplateEmail\CreateTemplateEmailRequest;
use App\Http\Requests\WebRequests\TemplateEmail\UpdateTemplateEmailRequest;
use App\Http\Resources\WebResources\TemplateEmailDetailResource;
use App\Http\Resources\WebResources\TemplateEmailListResource;
use App\Services\WebService\TemplateService;
use App\Supports\Facades\Response\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    private $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function getTemplateEmailList(Request $request): JsonResponse
    {
        $limit = (int) $request->get('limit', App::PER_PAGE);
        $search = $request->get('search');
        $sort = $request->get('sort', []);
        $list = $this->templateService->retrieveTemplateEmailList($limit, $search, $sort);

        return Response::pagination(TemplateEmailListResource::collection($list), $list->total(), $list->currentPage(), $list->perPage());
    }

    public function createTemplateEmail(CreateTemplateEmailRequest $request): JsonResponse
    {
        $status = $this->templateService->createTemplateEmail($request->all([
            'title',
            'subject',
            'content',
            'default',
            'cc_list',
            'bcc_list',
        ]));
        if (!$status) {
            return Response::failure('messages.system_error');
        }

        return Response::success();
    }

    public function updateTemplateEmail(UpdateTemplateEmailRequest $request, int $id): JsonResponse
    {
        $status = $this->templateService->updateTemplateEmail($id, $request->all([
            'updated_at',
            'title',
            'subject',
            'content',
            'default',
            'cc_list',
            'bcc_list',
        ]));
        if (!$status) {
            return Response::failure('messages.system_error');
        }

        return Response::success();
    }

    public function getTemplateEmailDetail(Request $request, int $id): JsonResponse
    {
        $entity = $this->templateService->getTemplateEmailDetail($id);
        if (!$entity) {
            abort(JsonResponse::HTTP_NOT_FOUND);
        }

        return Response::success([
            'template_email_data' => new TemplateEmailDetailResource($entity),
        ]);
    }

    public function deleteTemplateEmail(Request $request, int $id): JsonResponse
    {
        $status = $this->templateService->deleteTemplateEmail($id);
        if (!$status) {
            return Response::failure('messages.system_error');
        }
        return Response::success();
    }

    public function getAllTemplateEmails(Request $request): JsonResponse
    {
        $list = $this->templateService->getAllTemplateEmails();
        return Response::success([
            'data_list' => TemplateEmailListResource::collection($list),
        ]);
    }

    public function getTemplateEmailConfig(Request $request): JsonResponse
    {
        $configs = $this->templateService->getTemplateEmailConfigs();
        return Response::success($configs);
    }

    public function updateTemplateEmailDefault(Request $request, int $id): JsonResponse
    {
        $status = $this->templateService->updateTemplateEmailDefault($id);
        if (!$status) {
            return Response::failure('messages.system_error');
        }

        return Response::success();
    }

    public function getTemplateEmailSample(Request $request): JsonResponse
    {
        return Response::success([
            'sample' => [
                'content' => "<span>shared.guest.name</span>様<br>
<br>
<span>company.company_name</span>より画像アルバムを共有されましたので、以下のリンクにクリックして頂き、
本メールに添付しているパスワードをご入力頂ければご確認頂けます。<br>
<br>
メッセージ: <span>shared.guest.content</span><br>
リンク：<div>shared.link</div><br>
パスワード：<b>shared.password</b>
",
                'title' => 'SCSOFT VIET NAM JSCより画像アルバム共有のお知らせ（アルバムID： 109)',
            ],
        ]);
    }
}
