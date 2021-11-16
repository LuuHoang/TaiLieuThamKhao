<?php

namespace App\Services\WebService;

use App\Constants\AlbumPDFKeyConfig;
use App\Constants\App;
use App\Constants\Disk;
use App\Constants\EmailFollower;
use App\Constants\Example;
use App\Constants\InputType;
use App\Models\AbstractModel;
use App\Models\TemplateEmailModel;
use App\Repositories\Criteria\TemplateEmailListCriteria;
use App\Repositories\Repository;
use App\Services\AbstractService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class TemplateService extends AbstractService
{
    private $templateEmailRepository;

    public function __construct(TemplateEmailModel $templateEmailModel)
    {
        $this->templateEmailRepository = new Repository($templateEmailModel);
    }

    public function retrieveTemplateEmailList(int $limit, ?string $search, array $sort): LengthAwarePaginator
    {
        return $this->templateEmailRepository->where('template_emails.company_id', '=', app('currentUser')->company_id)->pushCriteria(new TemplateEmailListCriteria($sort, $search))->paginate($limit);
    }

    public function createTemplateEmail(array $data): bool
    {
        try {
            $templateEmail = $this->templateEmailRepository->create(array_merge([
                'company_id' => app('currentUser')->company_id,
                'created_user' => app('currentUser')->id,
                'updated_user' => app('currentUser')->id,
                'cc' => $data['cc_list'],
                'bcc' => $data['bcc_list'],
            ], $data));
            if (array_key_exists('default', $data) && $data['default'] === App::FLAG_YES) {
                $this->templateEmailRepository->where('id', '<>', $templateEmail->id)->update([
                    'default' => App::FLAG_YES,
                ], [
                    'default' => App::FLAG_NO,
                ]);
            }
            $this->createTemplateEmailContent($templateEmail->id, $templateEmail->content);
            return true;
        } catch (Exception $e) {
            $this->rollbackTransaction();
            report($e);
            return false;
        }
    }

    public function getTemplateEmailDetail(int $id): ?AbstractModel
    {
        return $this->templateEmailRepository->with(['createdUser', 'updatedUser'])->find($id);
    }

    public function deleteTemplateEmail(int $id): bool
    {
        $entity = $this->getTemplateEmailDetail($id);
        if (!$entity) {
            abort(JsonResponse::HTTP_NOT_FOUND);
        }
        try {
            $this->beginTransaction();
            $entity->delete();
            $this->commitTransaction();
            return true;
        } catch (Exception $e) {
            $this->rollbackTransaction();
            report($e);
            return false;
        }
    }

    public function updateTemplateEmail(int $id, array $data): bool
    {
        $entity = $this->getTemplateEmailDetail($id);
        if (!$entity) {
            abort(JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $this->beginTransaction();
            if (array_key_exists('default', $data) && $data['default'] === App::FLAG_YES) {
                $this->templateEmailRepository->where('id', '<>', $entity->id)->update([
                    'default' => App::FLAG_YES,
                ], [
                    'default' => App::FLAG_NO,
                ]);
            }
            $entity->update(array_merge($data, [
                'cc' => $data['cc_list'],
                'bcc' => $data['bcc_list'],
            ]));
            $this->createTemplateEmailContent($id, $entity->content);

            $this->commitTransaction();
            return true;
        } catch (Exception $e) {
            $this->rollbackTransaction();
            report($e);
            return false;
        }
    }

    public function createTemplateEmailContent(int $id, string $content): void
    {
        $content = $this->replaceShortCode($content);
        $this->storeContentToBlade($id, $content);
    }

    private function storeContentToBlade(int $id, string $content)
    {
        $path = "{$id}.blade.php";
        Storage::disk(Disk::TEMPLATE_EMAIL)->put($path, $content);
    }

    private function replaceShortCode(string $content): string
    {
        foreach (AlbumPDFKeyConfig::LIST_KEY_REGEX as $regex) {
            if ($regex === '/shared\.link/i') {
                $content = preg_replace($regex, '<a href="{{ $data["$0"] }}" target="_blank">{{ $data["$0"] }}</a>', $content);
                continue;
            }
            $content = preg_replace($regex, '{{ $data["$0"] ?? null }}', $content);
        }

        return $content;
    }

    public function getAllTemplateEmails(): Collection
    {
        return $this->templateEmailRepository->where('company_id', '=', app('currentUser')->company_id)->all();
    }

    public function getTemplateEmailConfigs(): array
    {
        $currentUser = app('currentUser');
        $companyEntity = $currentUser->company;
        $data = [];
        $locale = app('currentUser')->userSetting->language;
        $albumPropertyEntities = $companyEntity->albumProperties;

        $data['company'] = [
            "company.company_name" =>  $companyEntity->company_name,
            "company.company_code" =>  $companyEntity->company_code,
            "company.address" =>  $companyEntity->address,
            "company.representative" =>  $companyEntity->representative,
            "company.tax_code" =>  $companyEntity->tax_code,
        ];
        $data['shared'] = [
            'shared.link' => trans('messages.sample_shared_album_link', [], $locale),
            'shared.password' => trans('messages.sample_shared_album_password', [], $locale),
            'shared.guest.name' => trans('messages.sample_guest_name', [], $locale),
            'shared.guest.email' => trans('messages.sample_guest_email', [], $locale),
            'shared.guest.content' => trans('messages.sample_guest_content', [], $locale),
        ];
        $data['album'] = [
            "album.album_type"          =>  trans('messages.sample_album_type', [], $locale),
            "album.user_creator"        =>  trans('messages.sample_user_creator', [], $locale),
        ];
        if ($albumPropertyEntities->isNotEmpty()) {
            foreach ($albumPropertyEntities as $albumProperty) {
                $keyTitle = "album.information." . $albumProperty->id . ".title";
                $keyValue = "album.information." . $albumProperty->id . ".value";
                $sampleValue = "";
                if ($albumProperty->type == InputType::TEXT) {
                    $sampleValue = trans('messages.sample_album_information', [], $locale);
                } elseif ($albumProperty->type == InputType::DATE || $albumProperty->type == InputType::SHORT_DATE || $albumProperty->type == InputType::DATE_TIME) {
                    $sampleValue = now()->format(InputType::CONFIG[$albumProperty->type]['format'][$locale]);
                } elseif ($albumProperty->type == InputType::NUMBER) {
                    $sampleValue = mt_rand(0, 999999999);
                } elseif ($albumProperty->type == InputType::EMAIL) {
                    $sampleValue = Example::EMAIL;
                }
                $data['album']['information'][] = [
                    $keyTitle   =>  $albumProperty->title,
                    $keyValue   =>  $sampleValue
                ];
            }
        }
        return $data;
    }

    public function updateTemplateEmailDefault(int $id): bool
    {
        try {
            $this->beginTransaction();
            $template = $this->templateEmailRepository->find($id);
            $currentDefault = $this->templateEmailRepository->where('default', '=', App::FLAG_YES)->first();
            if ($currentDefault && $currentDefault->id !== $id) {
                $currentDefault->update([
                    'default' => App::FLAG_NO,
                ]);

                $template->update([
                    'default' => App::FLAG_YES,
                ]);
            }
            $this->commitTransaction();
            return true;
        } catch (Exception $e) {
            $this->rollbackTransaction();
            report($e);
            return false;
        }
    }

    public function getDefaultTemplateEmail()
    {
        return $this->templateEmailRepository->where('default', '=', App::FLAG_YES)->first();
    }
}
