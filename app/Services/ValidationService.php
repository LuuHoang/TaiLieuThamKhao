<?php

namespace App\Services;

use App\Constants\InputType;
use App\Exceptions\UnprocessableException;
use DateTime;
use Illuminate\Http\UploadedFile;

class ValidationService extends AbstractService
{
    public function checkValidateInputType(string $title, $content, int $inputType, string $locale)
    {
        if ($inputType === InputType::TEXT && !is_string($content)) {
            throw new UnprocessableException(trans('messages.input_text_format_is_incorrect', ['title' => $title], $locale));
        }
        if ($inputType === InputType::NUMBER && !is_numeric($content)) {
            throw new UnprocessableException(trans('messages.input_number_format_is_incorrect', ['title' => $title], $locale));
        }
        if ($inputType === InputType::EMAIL && (!is_string($content) || !$this->isValidEmail($content))) {
            throw new UnprocessableException(trans('messages.input_email_format_is_incorrect', ['title' => $title], $locale));
        }
        if (($inputType === InputType::DATE || $inputType === InputType::SHORT_DATE || $inputType == InputType::DATE_TIME) && (!is_numeric($content) || !$this->isValidTimeStamp($content))) {
            throw new UnprocessableException(trans('messages.input_date_format_is_incorrect', ['title' => $title], $locale));
        }
        if (($inputType === InputType::IMAGES || $inputType === InputType::VIDEOS) && (!is_array($content) || !$this->isValidFiles($content, InputType::CONFIG[$inputType]['extension']))) {
            throw new UnprocessableException(trans('messages.input_file_format_is_incorrect', ['title' => $title], $locale));
        }
    }

    private function isValidTimeStamp($timestamp)
    {
        return ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    private function isValidFiles(Array $files, Array $fileExtension)
    {
        foreach ($files as $file) {
            if (!($file instanceof UploadedFile)) {
                return false;
            }
            $extension = $file->clientExtension();
            if (!in_array($extension, $fileExtension)) {
                return false;
            }
        }
        return true;
    }

    private function isValidEmail($content)
    {
        return preg_match(InputType::CONFIG[InputType::EMAIL]['format'], $content);
    }
}
