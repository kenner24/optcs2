<?php

namespace App\Services\Connectors\Google;

use App\Enums\ConnectorEnum;
use App\Services\ConnectorAccessTokenService;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;

class GoogleSheetService
{
    private $clientId;
    private $clientSecret;
    private $redirectURL;
    private $googleClient;

    public function __construct(
        private ConnectorAccessTokenService $connectorAccessTokenService
    ) {
        $this->clientId = config('googleSheets.client_id');
        $this->clientSecret = config('googleSheets.client_secret');
        $this->redirectURL = config('googleSheets.redirect_uri');
        $this->googleClient = new Client();
        $this->googleClient->setClientId($this->clientId);
        $this->googleClient->setClientSecret($this->clientSecret);
        $this->googleClient->setRedirectUri($this->redirectURL);
    }

    private function setAccessToken(
        $accessToken,
        $refreshToken,
        $userId
    ) {
        $this->googleClient->setAccessToken($accessToken);

        if ($this->googleClient->isAccessTokenExpired()) {
            $result = $this->googleClient->fetchAccessTokenWithRefreshToken($refreshToken);
            $this->connectorAccessTokenService->updateOrCreate([
                "user_id" => $userId,
                "connector_type" => ConnectorEnum::GOOGLESHEET,
                "access_token" => $result['access_token'],
                "refresh_token" => $result['refresh_token'],
                "issued_at" => $result['created'],
                "access_token_expired_at" => $result['expires_in']
            ]);
            $this->googleClient->setAccessToken($result['access_token']);
        }
    }

    public function getSpreadSheetList($googleSheetDetails, $userId)
    {
        $this->setAccessToken(
            $googleSheetDetails['access_token'],
            $googleSheetDetails['refresh_token'],
            $userId,
        );

        $driveObj = new Drive($this->googleClient);
        $finalList = [];

        $this->fetchAllSheetsFromGoogle($driveObj, $finalList);
        return $finalList;
    }

    private function fetchAllSheetsFromGoogle(
        $driverObj,
        &$list,
        $nextCursor = null
    ) {
        if ($nextCursor !== null) {
            $sheetsList = $driverObj->files->listFiles([
                'q' => 'mimeType="application/vnd.google-apps.spreadsheet"',
                'pageSize' => 50,
            ]);

            if (count($sheetsList['files']) > 0) {
                foreach ($sheetsList['files'] as $key => $value) {
                    array_push($list, [
                        'spreadsheet_id' => $value['id'],
                        'spreadsheet_name' => $value['name']
                    ]);
                }
            }
            if ($sheetsList['nextPageToken'] !== null) {
                $this->fetchAllSheetsFromGoogle(
                    $driverObj,
                    $list,
                    $sheetsList['nextPageToken']
                );
            }
        } else {
            $sheetsList = $driverObj->files->listFiles([
                'q' => 'mimeType="application/vnd.google-apps.spreadsheet"',
                'pageSize' => 50,
                'pageToken' => $nextCursor
            ]);

            if (count($sheetsList['files']) > 0) {
                foreach ($sheetsList['files'] as $key => $value) {
                    array_push($list, [
                        'spreadsheet_id' => $value['id'],
                        'spreadsheet_name' => $value['name']
                    ]);
                }
            }

            if ($sheetsList['nextPageToken'] !== null) {
                $this->fetchAllSheetsFromGoogle(
                    $driverObj,
                    $list,
                    $sheetsList['nextPageToken']
                );
            }
        }
        return;
    }

    /**
     * Fetch the list of the sheets present in the given spread sheet
     * @param Object $googleSheetDetail (Google connector details present in the database)
     * @param int $userId
     * @param string $spreadSheetId
     */
    public function fetchSheetList(
        $googleSheetDetails,
        $userId,
        $spreadSheetId
    ) {
        $this->setAccessToken(
            $googleSheetDetails['access_token'],
            $googleSheetDetails['refresh_token'],
            $userId,
        );

        $sheetsObj = new Sheets($this->googleClient);
        $result = $sheetsObj->spreadsheets->get($spreadSheetId)->getSheets();
        $temp = [];
        foreach ($result as $key => $value) {
            array_push($temp, [
                "sheet_id" => $value->getProperties()->getSheetId(),
                "sheet_name" => $value->getProperties()->getTitle()
            ]);
        }
        return $temp;
    }


    /**
     * Fetch the first row of the sheet which is representing the column headers
     * @param Object $googleSheetDetail (Google connector details present in the database)
     * @param int $userId
     * @param string $spreadSheetId
     * @param string @sheetName
     */
    public function fetchColumnHeadings(
        $googleSheetDetails,
        $userId,
        $spreadSheetId,
        $sheetName
    ) {
        $this->setAccessToken(
            $googleSheetDetails['access_token'],
            $googleSheetDetails['refresh_token'],
            $userId,
        );

        $sheetsObj = new Sheets($this->googleClient);
        $result = $sheetsObj->spreadsheets_values->batchGet($spreadSheetId, [
            'ranges' => "'$sheetName'" . "!1:1"
        ])->getValueRanges();

        $columnList = [];

        if (!empty($result) && !empty($result[0]['values'])) {
            $columnList = $result[0]['values'][0];
        }

        return $columnList;
    }
}
