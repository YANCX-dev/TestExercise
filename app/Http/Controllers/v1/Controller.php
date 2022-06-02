<?php

namespace App\Http\Controllers\v1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Создание черновика документа
     * @param Request $request
     * @return array[]
     */
    public function create(Request $request): array
    {
        $timeNow = new \DateTime();
        $uuid = Str::uuid();

        $data = [
            'document' => [
                'id' => $uuid,
                'status' => 'draft',
                'payload' => [],
                'createAt' => $timeNow->format('c'),
                'modifyAt' => $timeNow->format('c'),
            ],
        ];

        $file = Storage::put('public/ApiResponses/' . $uuid . ".json", json_encode($data, JSON_UNESCAPED_UNICODE));

        return $data;
    }

    /**
     * Получение документа по id
     * @param string $id
     * @return JsonResponse|mixed
     */
    public function getDocumentById(string $id): mixed
    {
        $file = $this->getFileById($id);

        if ($file === null) {
            return response()->json('File was not found', 404);
        }


        return $file;
    }

    /**
     * Редактирование документа
     * @param string $id
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function editing(string $id, Request $request): mixed
    {
        $file = $this->getFileById($id);

        if ($file === null) {
            return response()->json('File was not found', 404);
        }

        if ($file['document']['status'] == 'published') {
            return response()->json('Error. File already published', 400);
        }

        $timeNow = new \DateTime();
        $payload = $request->document['payload'];

        //Для первого редактирования
        if (empty($file['document']['payload'])) {
            $file['document']['payload'] = $payload;
            $file['document']['modifyAt'] = $timeNow->format('c');
            Storage::put('public/ApiResponses/' . $id . ".json", json_encode($file, JSON_UNESCAPED_UNICODE));
            return $file;
        }

        //Все последующие
        foreach ($file['document']['payload'] as $fileKey => $fileValue) {
            if (is_array($payload[$fileKey])) {
                foreach ($payload[$fileKey] as $payloadKey => $payloadValue) {
                    if (is_array($payloadValue)) {
                        if ($payloadValue[key($payloadValue)] == null) {
                            unset($file['document']['payload'][$fileKey][$payloadKey][key($payloadValue)]);
                        } else {
                            $file['document']['payload'][$fileKey][$payloadKey][key($payloadValue)] = $payload[$fileKey][$payloadKey][key($payloadValue)];
                        }
                    } else {
                        if ($payloadValue == null) {
                            unset($file['document']['payload'][$fileKey][$payloadKey]);
                        } else {
                            $file['document']['payload'][$fileKey][$payloadKey] = $payload[$fileKey][$payloadKey];
                        }
                    }
                }
            } else {
                if ($payload[$fileKey] == null) {
                    unset($file['document']['payload'][$fileKey]);
                } else {
                    $file['document']['payload'][$fileKey] = $payload[$fileKey];
                }
            }
            foreach ($payload as $payloadKey => $payloadValue) {
                if (!isset($file['document']['payload'][$payloadKey])) {

                    if ($payloadValue == null) {
                        unset($file['document']['payload'][$payloadKey]);
                    } else {
                        $file['document']['payload'][$payloadKey] = $payload[$payloadKey];
                    }
                }
            }
        }

        $file['document']['modifyAt'] = $timeNow->format('c');
        Storage::put('public/ApiResponses/' . $id . ".json", json_encode($file, JSON_UNESCAPED_UNICODE));

        return $file;
    }

    /**
     * Публикация документа
     * @param string $id
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function publish(string $id, Request $request): mixed
    {
        $file = $this->getFileById($id);

        if ($file === null) {
            return response()->json('File was not found', 404);
        }

        $file['document']['status'] = 'published';
        Storage::put('public/ApiResponses/' . $id . ".json", json_encode($file, JSON_UNESCAPED_UNICODE));

        return $file;
    }

    /**
     * Получение файла по Id
     * @param string $id
     * @return ?array
     */
    public function getFileById(string $id): ?array
    {
        $file = Storage::get('public/ApiResponses/' . $id . ".json");

        return json_decode($file, true);
    }

}
