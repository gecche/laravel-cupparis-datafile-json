<?php

namespace App\DatafileJsonProviders;

use App\Rules\UniqueDatafileJson;
use Gecche\Cupparis\DatafileJson\Breeze\Contracts\BreezeDatafileJsonInterface;
use Gecche\Cupparis\DatafileJson\Breeze\BreezeDatafileJsonProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ComuneIstatXls extends BreezeDatafileJsonProvider
{
	/*
	 * array del tipo di datafile, ha la seguente forma: array( 'headers' => array( 'header1' => array( 'datatype' => 'string|int|data...', (default string) 'checks' => array( 'checkCallback1' => array(params => paramsArray,type => error|alert), ... 'checkCallbackN' => array(params => paramsArray,type => error|alert), ), (deafult array()) 'transforms' => array( 'transformCallback1' => array(params), ... 'transformCallbackN' => array(params), ), (default array()) 'blocking' => true|false (default false) ) ... 'headerN' => array( 'datatype' => 'string|int|data...', (default string) 'checks' => array( 'checkCallback1' => array(params), ... 'checkCallbackN' => array(params), ), (deafult array()) 'transforms' => array( 'transformCallback1' => array(params), ... 'transformCallbackN' => array(params), ), (default array()) ) 'peremesso' => 'permesso_string' (default 'datafile_upload') 'blocking' => true|false (default false) ) ) I chechCallbacks e transformCallbacks sono dei nomi di funzioni di questo modello (o sottoclassi) dichiarati come protected e con il nome del callback preceduto da _check_ o _transform_ e che accettano i parametri specificati I checkCallbacks hanno anche un campo che specifica se si tratta di errore o di alert I checks servono per verificare se i dati del campo corrispondono ai requisiti richiesti I transforms trasformano i dati in qualcos'altro (es: formato della data da gg/mm/yyyy a yyyy-mm-gg) Vengono eseguiti prima tutti i checks e poi tutti i transforms (nell'ordine specificato dall'array) Blocking invece definisce se un errore nei check di una riga corrisponde al blocco dell'upload datafile o se si può andare avanti saltando quella riga permesso è se il
	 */

    protected $modelTargetName = \App\Models\ComuneIstat::class;

	protected $zip = false;
	protected $zipDir = false;
	protected $zipDirName = '';

    protected $chunkRows = 1000;
    protected $fileProperties = [
        'skipEmptyLines' => true,  // Se la procedura salta le righe vuote che trova
        'stopAtEmptyLine' => true, // Se la procedura di importazione si ferma alla prima riga vuota incontrata
        'startingColumn' => 'A',
        'endingColumn' => 'D',
    ];
    protected $filetype = 'excel'; //csv, fixed_text, excel

    /*
     * HEADERS array header => datatype
     */
    public $headers = array(

        'GTComIstat',
        'GTComDes',
        'GTComPrv',
        'GTComCod',
    );

    protected $datafileRules = array(
        'GTComIstat' => ['required','numeric','unique_datafile_json'], //exists_datafile_json:datafileField,dbtable,dbfield
        'GTComDes' => 'required',
        'GTComPrv' => 'required',
        'GTComCod' => 'required',
    );

    public function associateRow(BreezeDatafileJsonInterface $modelDatafile, Model $modelTarget = null) {
        $datafileValues = $modelDatafile->getRowDataValues();
        $codice = Arr::get($datafileValues,'GTComIstat',-1);
        $modelTargetName = $this->modelTargetName;
        return $modelTargetName::findOrNew($codice);

    }

    public function formatRow(BreezeDatafileJsonInterface $modelDatafile, Model $modelTarget = null) {

        $datafileValues = $modelDatafile->getRowDataValues();
        $values = array();

        $values['codice_istat'] = Arr::get($datafileValues,'GTComIstat');
        $values['descrizione'] = Arr::get($datafileValues,'GTComDes');
        
        $values['provincia'] = Arr::get($datafileValues,'GTComPrv');
        $values['codice_catastale'] = Arr::get($datafileValues,'GTComCod');
        return $values;
    }


}

// End Datafile Core Model
