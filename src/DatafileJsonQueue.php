<?php namespace Gecche\Cupparis\DatafileJson;

use Gecche\Cupparis\DatafileJson\Facades\DatafileJson;
use Gecche\Cupparis\Queue\Queues\MainQueue;

use Illuminate\Support\Arr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DatafileJsonQueue extends MainQueue
{

    public const DATAFILE_JSON_LOAD = 'datafile_json_load';
    public const DATAFILE_JSON_SAVE = 'datafile_json_save';

    public function load($job, $data)
    {
        $this->jobStart($job, $data, self::DATAFILE_JSON_LOAD);
        try {

            $this->validateData(self::DATAFILE_JSON_LOAD);
            $this->ensureUser(Arr::get($data, 'userId'));

            $filename = Arr::get($this->data, 'fileName');

            if (Arr::get($this->data, 'fileInTempFolder', true)) {
                $filename = $this->filenameToTempFolder($filename);
            }

            $datafileProviderName = Arr::get($this->data, 'datafileProviderName');
            $datafileProviderName = $this->resolveProviderName($datafileProviderName);
            $datafileProvider = new $datafileProviderName;

//            Log::info('DatafileJson provider name: '. $datafileProviderName);

            $datafileProvider->formPost = $this->data;
            DatafileJson::setFormPost($this->data);
            DatafileJson::init($this->acQueue->getKey(), $datafileProvider, $filename, $this->acQueue->getKey());

            DatafileJson::getDatafileJson();
            $sheetsNames = DatafileJson::getSheetsNames();
            foreach ($sheetsNames as $sheetName) {

                DatafileJson::setCurrentSheet($sheetName);
                //DatafileJson::$user_id = $this->data['userId'];
                DatafileJson::beforeLoad();
                $initRow = 1;
                do {
                    DatafileJson::beforeLoadPart($initRow);
                    $nextInitRow = DatafileJson::loadPart($initRow);
                    DatafileJson::afterLoadPart($initRow);
                    $initRow = $nextInitRow;
                } while (!DatafileJson::isEof());
//			Log::info('hereENDENF');
                DatafileJson::afterLoad();
            }
//            Log::info('hereENDENF2');
            $this->jobEnd();
//            Log::info('hereENDENF3');

        } catch (Exception $e) {
            $this->jobEnd(1, $e->getMessage() . " in " . $e->getFile() . " " . $e->getLine());
            throw $e;
        }

    }

    public function save($job, $data)
    {
        $this->jobStart($job, $data, self::DATAFILE_JSON_SAVE);

        try {

            $this->validateData(self::DATAFILE_JSON_SAVE);
            $this->ensureUser(Arr::get($data, 'userId'));


            $datafileProviderName = Arr::get($this->data, 'datafileProviderName');
            $datafileProviderName = $this->resolveProviderName($datafileProviderName);

            $datafileProvider = new $datafileProviderName;
            $datafileProvider->formPost = $this->data;
            DatafileJson::setFormPost($this->data);
            DatafileJson::init($data['datafile_load_id'], $datafileProvider, null, $this->acQueue->getKey());

            DatafileJson::getDatafileJson();
            $sheetsNames = DatafileJson::getSheetsNames(true);
            DatafileJson::beforeSave();
            foreach ($sheetsNames as $sheetName) {
                DatafileJson::setCurrentSheet($sheetName, null, true);
                //Senza spezzarlo in parti
                DatafileJson::save();
            }
            DatafileJson::afterSave();
            $this->jobEnd();
        } catch (Exception $e) {
            $this->jobEnd(1, $e->getMessage());

        }
    }

    protected function validateData($job_type)
    {

        if ($job_type == self::DATAFILE_JSON_LOAD) {
            if (!Arr::get($this->data, 'fileName', false)) {
                throw new Exception("File datafile non definito!");
            }
        }
        if ($job_type == self::DATAFILE_JSON_SAVE) {
            if (!Arr::get($this->data, 'datafile_load_id', false)) {
                throw new Exception("DatafileJson id non definito!");
            }
        }

        if (!Arr::get($this->data, 'datafileProviderName', false)) {
            throw new Exception("DatafileJson provider name non definito!");
        }
        if (!Arr::get($this->data, "userId", false)) {
            throw new Exception("Utente non definito!");
        }
    }


    protected function filenameToTempFolder($filename)
    {
        $temp_dir = storage_temp_path();
//			echo $temp_dir;
        if (!is_dir($temp_dir)) {
            mkdir($temp_dir);
        }
//			Log::info('Input data: '. implode(';',$this->data));

        return rtrim($temp_dir, "/") . "/" . $filename;

    }

    protected function resolveProviderName($datafileProviderName)
    {
        $providerInConfig = Arr::get(Config::get('cupparis-datafile-json.providers', []), $datafileProviderName);
        return $providerInConfig ?: $datafileProviderName;
    }

    protected function ensureUser($userId) {
        $user = Auth::user();
        if (!$user) {
            Auth::loginUsingId($userId);
        }
    }
}
